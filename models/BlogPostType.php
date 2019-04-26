<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\ModuleTrait;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "blog_post_type".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $name
 * @property string $url_pattern
 * @property string $layout
 * @property boolean $has_title
 * @property boolean $has_content
 * @property boolean $has_brief
 * @property boolean $has_comment
 * @property boolean $has_banner
 * @property boolean $has_book
 * @property boolean $has_tag
 * @property boolean $has_category
 * @property boolean $locked
 *
 * @property BlogPost $post
 * @property BlogComment[] $blogComments
 * @property Module module
 */
class BlogPostType extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_post_type}}';
    }

    /**
     * created_at, updated_at to now()
     * crate_user_id, update_user_id to current login user id
     */
    public function behaviors()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            [['title', 'url_pattern', 'layout'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 64],
            [['has_comment', 'has_banner', 'has_category', 'has_tag', 'has_book', 'has_content', 'has_brief', 'has_title'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('', 'ID'),
            'title' => Module::t('', 'Title'),
            'has_comment' => Module::t('', 'Has Comment'),
            'has_banner' => Module::t('', 'Has Banner'),
            'has_category' => Module::t('', 'Has Category'),
            'has_tag' => Module::t('', 'Has Tag'),
            'has_book' => Module::t('', 'Has Book'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(BlogPost::class, ['type_id' => 'id']);
    }

    public function getUrl()
    {
        /*
         * If backend detected
         * Than return post type edit url
         * */
        if ($this->getModule()->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl(['blog/blog-post', 'type' => $this->name]);
        }

        return Yii::$app->getUrlManager()->createUrl(['/']);
    }

    /**
     * Creating image upload directory
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\base\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $path = Yii::getAlias($this->module->imgFilePath . '/post/' . $this->id);

        if (!is_dir($path)) {
            FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
        }

    }
}
