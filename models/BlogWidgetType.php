<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\Module;
use diazoxide\blog\traits\FeedTrait;
use diazoxide\blog\traits\ModuleTrait;

use diazoxide\blog\widgets\Feed;
use paulzi\jsonBehavior\JsonValidator;
use paulzi\jsonBehavior\JsonBehavior;

/**
 *
 * @property integer $id
 * @property string $config
 * @property string $title
 * @property string $widget
 *
 */
class BlogWidgetType extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_widget_type}}';
    }


    public function getBreadcrumbs()
    {
        $result = $this->getModule()->breadcrumbs;
        $result[] = ['label' => Module::t('Widget Types'), 'url' => ['index']];
        return $result;
    }


    public function behaviors()
    {
        return [
            [
                'class' => JsonBehavior::className(),
                'attributes' => ['config'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'],'string','max'=>255],
            [['config'], JsonValidator::className()],
            [['config_data'], 'safe'],
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'id' => Module::t('ID'),
            'config' => Module::t('Config'),
            'title' => Module::t('Title'),
        ];
    }

    public function getWidget(){
        $config = (array) $this->config;
        $config = reset($config);
        //$config['category_id'] = $this->id;
        return Feed::widget($config);
    }

}
