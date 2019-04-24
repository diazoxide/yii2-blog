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
 * @property integer $category_id
 * @property string $name
 * @property string $value
 */
class BlogCategoryData extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_category_data}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string','max'=>255],
            [['value'], 'string']
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
            'name' => Module::t('', 'Name'),
            'value' => Module::t('', 'Value'),

        ];
    }

    public function getCategory()
    {
        return $this->hasOne(BlogCategory::class, ['id' => 'category_id']);
    }

}
