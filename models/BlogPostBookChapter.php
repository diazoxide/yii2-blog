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
 * @property integer $book_id
 * @property integer $parent
 * @property string $title
 * @property string $url
 * @method getThumbFileUrl($attribute, $thumbType)
 * @property string $brief
 * @property string $banner

 *
 * @property BlogComment[] $blogComments
 * @property BlogCategory $category
 */
class BlogPostBookChapter extends \yii\db\ActiveRecord
{
    use StatusTrait, ModuleTrait;

    private $_oldTags;

    private $_status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_post_book_chapter}}';
    }

    /**
     * created_at, updated_at to now()
     * crate_user_id, update_user_id to current login user id
     */
    public function behaviors()
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'banner',
                'thumbs' => [
                    'xsthumb' => ['width' => 64, 'height' => 64],
                    'sthumb' => ['width' => 128, 'height' => 128],
                    'mthumb' => ['width' => 240, 'height' => 240],
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
            [['title', 'book_id'], 'required'],
            [['book_id', 'parent_id'], 'integer'],
            [['brief'], 'string'],
            [['banner'], 'file', 'extensions' => 'jpg, png, webp, jpeg', 'mimeTypes' => 'image/jpeg, image/png, image/webp',],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('blog', 'ID'),
            'title' => Module::t('blog', 'Title'),
            'brief' => Module::t('blog', 'Brief'),
            'banner' => Module::t('blog', 'Banner'),
            'book_id' => Module::t('blog', 'Book'),
            'content' => Module::t('blog', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(BlogPostBook::className(), ['id' => 'book_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChapters()
    {
        return $this->hasMany(BlogPostBookChapter::className(), ['parent_id' => 'id']);
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl(['blog/blog-post-book-chapter/update', 'id' => $this->id]);
        }

        return Yii::$app->getUrlManager()->createUrl(['blog/default/book', 'post' => $this->post->slug, 'slug' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl()
    {
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createAbsoluteUrl(['blog/blog-post-book/update', 'id' => $this->id]);
        }

        return Yii::$app->getUrlManager()->createAbsoluteUrl(['blog/default/book', 'post' => $this->post->slug, 'slug' => $this->slug]);
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

}
