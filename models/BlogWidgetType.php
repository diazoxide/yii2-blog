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


/**
 *
 * @property integer $id
 * @property string $config
 * @property string $title
 *
 */
class BlogWidgetType extends \yii\db\ActiveRecord
{
    use ModuleTrait, FeedTrait;

    public $config_data;

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
            [['title'], 'required'],
            [['title'],'string','max'=>255],
            [['config'], 'string'],
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

        array_merge($labels, $this->getLabels());
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // if new record is inserted into db
            } else {
                // if existing record is updated
                // you can use something like this
                // to prevent updating certain data
                // $this->status = $this->oldAttributes['status'];
            }

            $this->config = json_encode($this->config_data);

            return true;
        }

        return false;
    }


    /**
     * @return mixed
     */
    public function getConfigData()
    {
        return json_decode($this->config);
    }

    /**
     * @param mixed $config_data
     */
    public function setConfigData($config_data): void
    {
        $this->config = json_encode($config_data);
    }

}
