<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\models;

use diazoxide\blog\behaviors\DataOptionsBehavior\DataModel;

/**
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $value
 */
class BlogCategoryData extends DataModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_category_data}}';
    }

}
