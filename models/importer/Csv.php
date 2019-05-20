<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 11.04
 * Time: 21:26
 */

namespace diazoxide\blog\models\importer;


use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\Module;
use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\httpclient\Client;
use yii\web\Response;

class Csv extends Model
{
    public $url;

    public $per_page = 10;
    public $page = 1;

    public $unique_key;
    public $post_type_id;

    public $overwrite = true;
    public $import_categories = true;
    public $localize_content = true;

    public $total = 0;
    public $total_pages = 1;

    public $csv = null;
    public $data = null;

    public $fields;
    public $delimiter = ",";
    public $enclosure = '"';

    private $_fields;

    private $mimes = [
        'text/comma-separated-values',
        'application/octet-stream',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
    ];

    public function rules()
    {
        return [
            [['url', 'key', 'post_type_id', 'fields'], 'required'],
            [['url'], 'url'],
            [['page', 'per_page'], 'integer'],
            [['post_type_id'], 'integer'],
            [['fields'], 'string'],
            [['delimiter', 'enclosure', 'unique_key'], 'string', 'max' => 16],
            [['overwrite', 'import_categories'], 'boolean'],
            ['post_type_id', 'exist', 'targetClass' => BlogPostType::class, 'targetAttribute' => 'id'],
            ['import_categories', 'import_categories_validator'],

            /*            [['fields'], 'match', 'pattern' => '/^(?>\w+(?>, ?)?)+$/'],*/


        ];
    }

    public function getFieldsList()
    {
        $fields = (new BlogPost())->attributes();
        $fields[] = 'category_name';
        return $fields;
    }

    /**
     * @return bool|void
     * @throws \yii\base\InvalidConfigException
     */
    public function afterValidate()
    {

        /**
         * Match the "fields" property format
         * The property value must be like this
         * You must start the row with name of field
         * The default value you can set after field name inside "{ }" brackets
         *
         * For mapping values you must write map after field name inside square brackets "[ ]"
         * For example [value_1=>Albania|value_2=>Germany]
         *
         * The final value of field row must be like:
         * field_name{default value}[value_1=>1|value_2=>2]
         *
         * The final value of fields property must be like:
         * ```
         *  id
         *  title{default title}
         *  slug
         *  content{content is empty}
         *  status{1}[PUBLISHED=>1|DRAFT=>0|PENDING=>0]
         *  is_slide[yes=>1|no=>0]
         * ```
         * */
        if ($this->fields) {
            $conditions = preg_split('/[\r\n]+/', $this->fields);
            foreach ($conditions as $condition) {
                /**
                 * Extracting field name
                 * */
                preg_match('/^\w+/', $condition, $matches, PREG_OFFSET_CAPTURE, 0);
                $field = isset($matches[0][0]) ? $matches[0][0] : null;


                /*
                 * Checking if fields names is valid
                 * */
                if (!in_array($field, $this->getFieldsList())) {
                    $this->addError('fields', Module::t('', 'Unknown field name "{field}".', ['field' => $field]));
                    continue;
                }

                /**
                 * Extracting map
                 * */
                preg_match('/\[(.*?)\]/', $condition, $matches, PREG_OFFSET_CAPTURE, 0);
                $map_string = isset($matches[1][0]) ? $matches[1][0] : null;
                $map = [];
                if ($map_string) {
                    preg_match_all('/(.+?)=>(.+?)(?>\||$)/', $map_string, $matches, PREG_SET_ORDER, 0);
                    foreach ($matches as $match) {
                        $map[$match[1]] = $match[2];
                    }
                }

                /**
                 * Extracting default value
                 * */
                preg_match('/\{(.*?)\}/', $condition, $matches, PREG_OFFSET_CAPTURE, 0);
                $default = isset($matches[1][0]) ? $matches[1][0] : null;


                /**
                 * Extracting pattern value
                 * */
                preg_match('/\((.*?)\)/', $condition, $matches, PREG_OFFSET_CAPTURE, 0);
                $pattern = isset($matches[1][0]) ? $matches[1][0] : null;


                /**
                 * Extracting find and replace value
                 * */
                preg_match('/\<(.*?)\>/', $condition, $matches, PREG_OFFSET_CAPTURE, 0);
                $map_string = isset($matches[1][0]) ? $matches[1][0] : null;
                $replacements = [];
                if ($map_string) {
                    preg_match_all('/(.+?)=>(.+?)(?>\||$)/', $map_string, $matches, PREG_SET_ORDER, 0);
                    foreach ($matches as $match) {
                        $replacements[$match[1]] = $match[2];
                    }
                }


                $this->_fields[$field] = [
                    'map' => $map,
                    'replacements' => $replacements,
                    'default' => $default,
                    'pattern' => $pattern,
                ];


            }
        }

        Yii::info($this->_fields, self::class);

        $cache = Yii::$app->cache;

        $cache_id = md5(self::class . $this->getKey().$this->url.$this->delimiter.$this->enclosure);
        if (!$this->csv = $cache->get($cache_id)) {

            Yii::info("CSV data providing from remote url.", self::class);

            Yii::info("CSV file url: " . $this->url, self::class);

            $request = $this->sendGet($this->url);


            if (!$request) {
                $this->addError('url', Module::t('', 'Unexpected URL error.'));
            }
            $mime = $request['headers']['Content-Type'];

            if (in_array($mime, $this->mimes)) {

                Yii::info("CSV File is valid.", self::class);

                $csv = new \ParseCsv\Csv();
                $csv->fields = array_keys($this->_fields);
                $csv->enclosure = $this->enclosure;
                $csv->delimiter = $this->delimiter;
                $csv->heading = false;
                $csv->auto($request['data']);
                $this->csv = $csv;

                Yii::info("CSV File successfully parsed.", self::class);

                $cache->set($cache_id, $this->csv, null, null);

                Yii::info("CSV object successfully cached.", self::class);


            } else {
                Yii::info("CSV File is not valid.");

                $this->addError('url', Module::t('', 'The file mime type(' . $mime . ') is not valid.'));
            }

        } else {
            Yii::info("CSV object providing from cache.");

        }

        if ($this->csv) {
            $this->total = count($this->csv->data);
            $this->total_pages = round($this->total / $this->per_page);
        }


        parent::afterValidate();

    }


    public function import_categories_validator($attribute_name)
    {
        $type = BlogPostType::findOne($this->post_type_id);

        if (!$type) {
            return false;
        }
        if (!$type->has_category && $this->{$attribute_name}) {

            $this->addError($attribute_name, Yii::t('user', 'Categories for selected post type disabled.'));

            return false;
        }

        return true;
    }


    /**
     * @param $url
     * @return bool|array
     * @throws \yii\base\InvalidConfigException
     */
    private function sendGet($url)
    {
//        ini_set('memory_limit', '1024M');
        $client = new Client();
        /** @var Response $response */
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setFormat(Client::FORMAT_RAW_URLENCODED)
            ->setUrl($url)
            ->setData([])
            ->send();
        /** @var \yii\httpclient\Response $response */
        if ($response->isOk) {
            Yii::info("Response status code: " . $response->statusCode, self::class);
            return ['data' => $response->getContent(), 'headers' => $response->getHeaders()];
        } else {
            Yii::error($response->statusCode . ' - ', self::class);
        }
        return false;
    }


    /**
     * @return array
     */
    public function getPosts()
    {
        $offset = ($this->page - 1) * $this->per_page;
        $data = array_slice($this->csv->data, $offset, $this->per_page);
        $result = [];
        foreach ($data as $item) {
            $item_final = [];
            foreach ($item as $key => $property) {
                $map = $this->_fields[$key]['map'];
                $value = isset($map[$property]) ? $map[$property] : $property;

                $item_final[$key] = $value;

                if (!empty($this->_fields[$key]['pattern'])) {
                    $item_final[$key] = sprintf($this->_fields[$key]['pattern'], $item_final[$key]);
                }

                foreach ($this->_fields[$key]['replacements'] as $find => $replace) {
                    $item_final[$key] = preg_replace($find, $replace, $item_final[$key]);
                }

                if (empty($item_final) && !empty($this->_fields[$key]['default'])) {
                    $item_final[$key] = $this->_fields[$key]['default'];
                }
            }
            $result[] = $item_final;
        }
        return $result;

    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'url' => 'File URL',
        ];
    }


    /**
     * @return string
     */
    public function getKey()
    {
        return "csv_" . $this->post_type_id . '_' . $this->unique_key;
    }
}