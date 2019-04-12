<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 11.04
 * Time: 21:26
 */

namespace diazoxide\blog\models\importer;


use diazoxide\blog\Module;
use Yii;
use yii\base\Model;
use yii\httpclient\Client;

class Wordpress extends Model
{
    public $url;

    public $data;

    protected $rest_path = '/wp-json/wp/v2/';


    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url'], 'url'],
            [['url'], 'wordpress_rest'],
        ];
    }

    /**
     * @param $attribute_name
     * @param $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function wordpress_rest($attribute_name, $params)
    {
        if (!$this->setData()) {
            $this->addError($attribute_name, Module::t('', 'The wordpress rest api url not working.'));
            return false;
        }
        return true;
    }

    /**
     * @param $url
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private static function sendGet($url)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            ->setData([])
            ->send();
        if ($response->isOk) {
            return $response->data;
        }
        return false;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    private function setData()
    {
        $data = self::sendGet($this->getRestURL());

        if ($data) {
            if ($data['namespace'] != 'wp/v2') {
                return false;
            }
            $this->data = $data;
            return true;
        }

        return false;
    }

    /**
     * @param $name
     * @param null $params
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    public function getEndpoint($name, $params = null)
    {
        $endpoint_name = "/wp/v2/" . $name;
        if (isset($this->data['routes'][$endpoint_name])) {

            $args = $params ? http_build_query($params) : '';

            $url = $this->getRestURL() . $name . '?' . $args;

            return self::sendGet($url);
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'url' => 'Website URL',
        ];
    }

    public function getRestURL()
    {
        return $this->url . $this->rest_path;
    }
}