<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\ModuleTrait;
use diazoxide\blog\traits\StatusTrait;
use voskobovich\behaviors\ManyToManyBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "blog_post".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $url
 * @property boolean $show_comments
 * @method getThumbFileUrl($attribute, $thumbType)
 * @property string $content
 * @property string $brief
 * @property string $tags
 * @property string $slug
 * @property string $banner
 * @property integer $click
 * @property integer $user_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 *
 * @property BlogComment[] $blogComments
 * @property BlogCategory $category
 * @property BlogPostBook $books
 * @property Module module
 */
class BlogPost extends \yii\db\ActiveRecord
{
    use StatusTrait, ModuleTrait;

    private $_oldTags;

    private $_status;

    public $created;

    public $updated;

    public $published;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_post}}';
    }

    /**
     * created_at, updated_at to now()
     * crate_user_id, update_user_id to current login user id
     * @throws \yii\base\InvalidConfigException
     */
    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,

            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id'
                ],
                'value' => function ($event) {
                    return Yii::$app->user->getId();
                },
            ],
            [
                'class' => ManyToManyBehavior::className(),
                'relations' => [
                    'category_ids' => 'categories',
                ],
            ],

            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'banner',
                'thumbs' => [
                    'xsthumb' => ['width' => 64, 'height' => 64],
                    'sthumb' => ['width' => 128, 'height' => 128],
                    'mthumb' => ['width' => 240, 'height' => 240],
                    'nthumb' => ['width' => 320, 'height' => 320],
                    'xthumb' => ['width' => 480, 'height' => 480],
                    'thumb' => ['width' => 400, 'height' => 300],
                    'facebook' => ['width' => 600, 'height' => 315],
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
            [['category_ids'], 'each', 'rule' => ['integer']],

            [['category_id', 'title', 'content'], 'required'],
            [['category_id', 'click', 'user_id', 'status', 'created_at', 'updated_at', 'published_at'], 'integer'],

            [['brief', 'content'], 'string'],

            [['created', 'updated', 'published'], 'date', 'format' => Yii::$app->formatter->datetimeFormat],

            [['is_slide', 'show_comments'], 'boolean'],
            [['show_comments'], 'default', 'value' => true],
            [['banner'], 'file', 'extensions' => 'jpg, png, webp, jpeg', 'mimeTypes' => 'image/jpeg, image/png, image/webp',],
            [['title', 'tags'], 'string', 'max' => 500],
            [['slug'], 'string', 'max' => 128],
            [['slug'], 'unique'],
            ['click', 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('', 'ID'),
            'category_id' => Module::t('', 'Category'),
            'category_ids' => Module::t('', 'Categories'),
            'title' => Module::t('', 'Title'),
            'brief' => Module::t('', 'Brief'),
            'content' => Module::t('', 'Content'),
            'tags' => Module::t('', 'Tags'),
            'slug' => Module::t('', 'Slug'),
            'banner' => Module::t('', 'Banner'),
            'show_comments' => Module::t('', 'Show Comments'),
            'click' => Module::t('', 'Click'),
            'user_id' => Module::t('', 'Author'),
            'status' => Module::t('', 'Status'),
            'created_at' => Module::t('', 'Created At'),
            'updated_at' => Module::t('', 'Updated At'),
            'published_at' => Module::t('', 'Published At'),
            'is_slide' => Module::t('', 'Show In Slider'),
            'commentsCount' => Module::t('', 'Comments Count'),
            'created' => Module::t('', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogComments()
    {
        return $this->hasMany(BlogComment::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(BlogPostBook::className(), ['post_id' => 'id']);
    }

    /**
     * @return int|string
     */
    public function getCommentsCount()
    {
        return $this->hasMany(BlogComment::className(), ['post_id' => 'id'])->count('post_id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(BlogCategory::className(), ['id' => 'category_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getCategories()
    {
        return $this->hasMany(BlogCategory::className(), ['id' => 'category_id'])
            ->viaTable('{{%blog_category_map}}', ['post_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if (\dektrium\user\models\User::class) {
            return $this->hasOne(\dektrium\user\models\User::class, ['id' => 'user_id']);
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(BlogComment::className(), ['post_id' => 'id']);
    }

    /**
     * After save.
     *
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // add your code here
        BlogTag::updateFrequency($this->_oldTags, $this->tags);
    }

    /**
     * After save.
     *
     */
    public function afterDelete()
    {
        parent::afterDelete();
        // add your code here
        BlogTag::updateFrequencyOnDelete($this->_oldTags);
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }

    /**
     * Normalizes the user-entered tags.
     */
    public static function getArrayCategory()
    {
        return ArrayHelper::map(BlogCategory::find()->all(), 'id', 'title');
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute, $params)
    {
        $this->tags = BlogTag::array2string(array_unique(array_map('trim', BlogTag::string2array($this->tags))));
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl(['blog/blog-post/update', 'id' => $this->id]);
        }
        $year = date('Y', $this->published_at);
        $month = date('m', $this->published_at);
        $day = date('d', $this->published_at);
        return Yii::$app->getUrlManager()->createUrl(['blog/default/view', 'year' => $year, 'month' => $month, 'day' => $day, 'slug' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl()
    {
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createAbsoluteUrl(['blog/blog-post/update', 'id' => $this->id]);
        }
        $year = date('Y', $this->published_at);
        $month = date('m', $this->published_at);
        $day = date('d', $this->published_at);
        return Yii::$app->getUrlManager()->createAbsoluteUrl(['blog/default/view', 'year' => $year, 'month' => $month, 'day' => $day, 'slug' => $this->slug]);
    }


    /**
     * @return array
     */
    public function getTagLinks()
    {
        $links = [];
        foreach (BlogTag::string2array($this->tags) as $tag) {
            $links[] = Html::a($tag, Yii::$app->getUrlManager()->createUrl(['blog/default/index', 'tag' => $tag]));
        }

        return $links;
    }

    /**
     * comment need approval
     * @param BlogComment $comment
     * @return bool
     */
    public function addComment($comment)
    {
        $comment->status = IActiveStatus::STATUS_INACTIVE;
        $comment->post_id = $this->id;
        return $comment->save();
    }

    /**
     * @return string
     */
    public function getCreatedRelativeTime()
    {
        return Yii::$app->formatter->format($this->created_at, 'relativeTime');
    }

    /**
     * @return string
     */
    public function getUpdatedRelativeTime()
    {
        return Yii::$app->formatter->format($this->updated_at, 'relativeTime');
    }

    /**
     * @return array|mixed
     */
    public function getBreadcrumbs()
    {
        $result = $this->category->breadcrumbs;
        return $result;
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreated()
    {
        if ($this->isNewRecord) {
            return Module::convertTime(time(), 'datetime');
        }
        return Module::convertTime($this->created_at, 'datetime');
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getUpdated()
    {
        if ($this->isNewRecord) {
            return Module::convertTime(time(), 'datetime');
        }
        return Module::convertTime($this->updated_at, 'datetime');
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getPublished()
    {
        if ($this->isNewRecord) {
            return Module::convertTime(time(), 'datetime');
        }
        return Module::convertTime($this->published_at, 'datetime');
    }

}
