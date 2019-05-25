<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\ModuleTrait;
use diazoxide\blog\traits\StatusTrait;
use diazoxide\blog\widgets\Feed;
use paulzi\adjacencyList\AdjacencyListBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\widgets\Breadcrumbs;
use yiidreamteam\upload\ImageUploadBehavior;


/**
 * This is the model class for table "blog_category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type_id
 * @property string $title
 * @property string $slug
 * @property string $banner
 * @property integer $is_nav
 * @property integer $sort_order
 * @property integer $page_size
 * @property string $template
 * @property string $redirect_url
 * @property string $icon_class
 * @property string $icon
 * @property string $data
 * @property string $read_more_text
 * @property string $read_icon_class
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property BlogPost[] $blogPosts
 * @property string titleWithIcon
 * @property string url
 * @property BlogCategory childs
 * @property BlogWidgetType widgetType
 * @method \yii\db\ActiveQuery getChildren()
 * @property \yii\db\ActiveQuery children
 * @property Breadcrumbs breadcrumbs
 * @property BlogCategory parent
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    use StatusTrait, ModuleTrait;

    const IS_NAV_YES = 1;
    const IS_NAV_NO = 0;

    const IS_FEATURED_YES = 1;
    const IS_FEATURED_NO = 0;


    const PAGE_TYPE_LIST = 'list';
    const PAGE_TYPE_PAGE = 'page';

    private $_isNavLabel;
    private $_status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_category}}';
    }


    /**
     * @return array
     */
    public static function getArrayIsNav()
    {
        return [
            self::IS_NAV_YES => Module::t('', 'YES'),
            self::IS_NAV_NO => Module::t('', 'NO'),
        ];
    }

    /**
     * @return array
     */
    public static function getArrayIsFeatured()
    {
        return [
            self::IS_FEATURED_YES => Module::t('', 'YES'),
            self::IS_FEATURED_NO => Module::t('', 'NO'),
        ];
    }


    /**
     * @param int $parentId
     * @param array $array
     * @return int|string
     */
    static public function getCategoryIdStr($parentId = 0, $array = array())
    {
        $str = $parentId;
        foreach ((array)$array as $v) {
            if ($v['parent_id'] == $parentId) {

                $tempStr = self::getCategoryIdStr($v['id'], $array);
                if ($tempStr) {
                    $str .= ',' . $tempStr;
                }
            }
        }
        return $str;
    }

    public function getBreadcrumbs()
    {
        if ($this->parent) {
            $result = $this->parent->breadcrumbs;
        } else {
            $result = $this->getModule()->categoryBreadcrumbs;
        }
        if (!$this->isNewRecord && $this->id != 1) {

            $result[] = [
                'label' => $this->title,
                'url' => $this->url
            ];
        }
        return $result;
    }

    public function getType()
    {
        return $this->hasOne(BlogPostType::class, ['id' => 'type_id']);
    }


    public function behaviors()
    {
        return [
            [
                'class' => \diazoxide\blog\behaviors\DataOptionsBehavior\Behavior::class,
                'data_model'=>BlogCategoryData::class
            ],
            [
                'class' => AdjacencyListBehavior::className(),
                'sortable' => [
                    'sortAttribute' => 'sort_order'
                ]
            ],
            TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'banner',
                'thumbs' => [
                    'thumb' => ['width' => 400, 'height' => 300]
                ],
                'filePath' => $this->module->imgFilePath . '/[[model]]/[[pk]].[[extension]]',
                'fileUrl' => $this->module->getImgFullPathUrl() . '/[[model]]/[[pk]].[[extension]]',
                'thumbPath' => $this->module->imgFilePath . '/[[model]]/[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => $this->module->getImgFullPathUrl() . '/[[model]]/[[profile]]_[[pk]].[[extension]]',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'type_id', 'is_nav', 'is_featured', 'sort_order', 'widget_type_id', 'page_size', 'status', 'sort'], 'integer'],
            [['title'], 'required'],
            [['parent_id'], 'parentValidation'],
            [['sort_order', 'page_size'], 'default', 'value' => 0],
            [['icon_class', 'read_icon_class', 'read_more_text'], 'string', 'max' => 60],
            [['title', 'template', 'redirect_url', 'slug'], 'string', 'max' => 191],
            [['banner'], 'file', 'extensions' => 'jpg, png, webp', 'mimeTypes' => 'image/jpeg, image/png, image/webp'],
        ];
    }

    public function parentValidation($attribute, $params)
    {
        if ($this->id == $this->parent_id) {
            // no real check at the moment to be sure that the error is triggered
            $this->addError($attribute, Module::t('', 'The element cannot use itself as a parent.'));
        }

        if ($this->parent->type_id != $this->type_id && $this->parent->type_id != null) {
            $this->addError($attribute, Module::t('', 'Category type must be same type as the parent category.'));

        }

        if ($this->id != 1 && $this->parent_id == null) {
            $this->addError($attribute, Module::t('', 'You can not create root element.'));

        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('', 'ID'),
            'parent_id' => Module::t('', 'Parent'),
            'title' => Module::t('', 'Title'),
            'slug' => Module::t('', 'Slug'),
            'banner' => Module::t('', 'Banner'),
            'icon_class' => Module::t('', 'Icon Class'),
            'read_icon_class' => Module::t('', 'Read Icon Class'),
            'read_more_text' => Module::t('', 'Read More Text'),
            'is_nav' => Module::t('', 'Is Nav'),
            'is_featured' => Module::t('', 'Is Featured'),
            'sort_order' => Module::t('', 'Sort Order'),
            'sort' => Module::t('', 'Sort'),
            'page_size' => Module::t('', 'Page Size'),
            'template' => Module::t('', 'Template'),
            'redirect_url' => Module::t('', 'Redirect Url'),
            'status' => Module::t('', 'Status'),
            'created_at' => Module::t('', 'Created At'),
            'updated_at' => Module::t('', 'Updated At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogPosts()
    {
        return $this->hasMany(BlogPost::class, ['category_id' => 'id']);
    }


    /**
     * @return integer
     */
    public function getPostsCount()
    {
        return $this->count(BlogPost::class, ['category_id' => 'id']);
    }


    /**
     * @return string
     */
    public function getIcon()
    {
        $iconClass = $this->icon_class ? $this->icon_class : "fa fa-bookmark";
        return "<i class=\"$iconClass\"></i>";
    }

    public function getTitleWithIcon()
    {
        return $this->icon . " " . $this->title;
    }

    public function getUrl()
    {
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl([$this->getModule()->id . '/blog-category/update', 'id' => $this->id]);
        }
        return Yii::$app->getUrlManager()->createUrl([$this->getModule()->id . '/default/archive', 'type' => $this->type ? $this->type->name : null, 'slug' => $this->slug]);

    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetType()
    {
        return $this->hasOne(BlogWidgetType::class, ['id' => 'widget_type_id']);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function getWidget()
    {
        $config = (array)$this->widgetType->config;
        $config = reset($config);
        $config['category_id'] = $this->id;
        $config['id'] = $this->formName() . '_' . $this->id;
        return Feed::widget($config);
    }

    /**
     * @return mixed
     */
    public function getIsNavLabel()
    {
        if ($this->_isNavLabel === null) {
            $arrayIsNav = self::getArrayIsNav();
            $this->_isNavLabel = $arrayIsNav[$this->is_nav];
        }
        return $this->_isNavLabel;
    }
}
