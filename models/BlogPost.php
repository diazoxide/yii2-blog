<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

namespace diazoxide\yii2blog\models;

use diazoxide\yii2blog\Module;
use diazoxide\yii2blog\traits\IActiveStatus;
use diazoxide\yii2blog\traits\ModuleTrait;
use diazoxide\yii2blog\traits\StatusTrait;
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
 *
 * @property BlogComment[] $blogComments
 * @property BlogCategory $category
 */
class BlogPost extends \yii\db\ActiveRecord
{
    use StatusTrait, ModuleTrait;

    private $_oldTags;

    private $_status;

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
     */
    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true
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
                'class' => ImageUploadBehavior::class,
                'attribute' => 'banner',
                'thumbs' => [
                    'xsthumb' => ['width' => 64, 'height' => 64],
                    'sthumb' => ['width' => 128, 'height' => 128],
                    'mthumb' => ['width' => 240, 'height' => 240],
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
            [['category_id', 'title', 'content'], 'required'],
            [['category_id', 'click', 'user_id', 'status'], 'integer'],
            [['brief', 'content'], 'string'],
            [['is_slide'], 'boolean'],
            [['banner'], 'file', 'extensions' => 'jpg, png, webp, jpeg', 'mimeTypes' => 'image/jpeg, image/png, image/webp',],
            [['title', 'tags'], 'string', 'max' => 255],
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
            'id' => Module::t('blog', 'ID'),
            'category_id' => Module::t('blog', 'Category'),
            'title' => Module::t('blog', 'Title'),
            'brief' => Module::t('blog', 'Brief'),
            'content' => Module::t('blog', 'Content'),
            'tags' => Module::t('blog', 'Tags'),
            'slug' => Module::t('blog', 'Slug'),
            'banner' => Module::t('blog', 'Banner'),
            'click' => Module::t('blog', 'Click'),
            'user_id' => Module::t('blog', 'Author'),
            'status' => Module::t('blog', 'Status'),
            'created_at' => Module::t('blog', 'Created At'),
            'updated_at' => Module::t('blog', 'Updated At'),
            'is_slide' => Module::t('blog', 'Show In Slider'),
            'commentsCount' => Module::t('blog', 'Comments Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogComments()
    {
        return $this->hasMany(BlogComment::className(), ['post_id' => 'id']);
    }

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
     */
    public function getUser()
    {
        if ($this->getModule()->userModel) {
            return $this->hasOne($this->getModule()->userModel::className(), [$this->getModule()->userPK => 'user_id']);
        }
        return null;
    }

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
     *
     */
    public function getUrl()
    {
        if($this->getModule()->getIsBackend()){
            return Yii::$app->getUrlManager()->createUrl(['blog/blog-post/update', 'id'=>$this->id]);
        }
        $year = date('Y', $this->created_at);
        $month = date('m', $this->created_at);
        $day = date('d', $this->created_at);
        return Yii::$app->getUrlManager()->createUrl(['blog/default/view', 'year' => $year, 'month' => $month, 'day' => $day, 'slug' => $this->slug]);
    }

    public function getAbsoluteUrl()
    {
        if($this->getModule()->getIsBackend()){
             return Yii::$app->getUrlManager()->createAbsoluteUrl(['blog/blog-post/update', 'id'=>$this->id]);
        }
        $year = date('Y', $this->created_at);
        $month = date('m', $this->created_at);
        $day = date('d', $this->created_at);
        return Yii::$app->getUrlManager()->createAbsoluteUrl(['blog/default/view', 'year' => $year, 'month' => $month, 'day' => $day, 'slug' => $this->slug]);
    }

    /**
     *
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
     */
    public function addComment($comment)
    {
        $comment->status = IActiveStatus::STATUS_INACTIVE;
        $comment->post_id = $this->id;
        return $comment->save();
    }

    public function getCreatedRelativeTime()
    {
        return Yii::$app->formatter->format($this->created_at, 'relativeTime');
    }

    public function getUpdatedRelativeTime()
    {
        return Yii::$app->formatter->format($this->updated_at, 'relativeTime');
    }

    public function getSluga()
    {
        return 1;
    }
}