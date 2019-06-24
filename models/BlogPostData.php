<?php

namespace diazoxide\blog\models;

use diazoxide\blog\behaviors\DataOptionsBehavior\DataModel;

/**
 * @property integer $id
 * @property integer $owner_id
 * @property string $name
 * @property string $value
 */
class BlogPostData extends DataModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blog_post_data}}';
    }

}
