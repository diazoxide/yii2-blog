<?php

use yii\db\Schema;
use yii\db\Migration;

class m000002_000002_blog_category_DATA_INSERT extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%blog_category}}',
            ["id", "parent_id", "title", "slug", "banner", "icon_class", "is_nav", "is_featured", "widget_type_id", "read_icon_class", "read_more_text", "sort_order", "sort", "page_size", "template", "redirect_url", "status", "created_at", "updated_at"],
            [
                [
                    'id' => '1',
                    'parent_id' => '0',
                    'title' => 'Main',
                    'slug' => 'main',
                    'banner' => null,
                    'icon_class' => '',
                    'is_nav' => '0',
                    'is_featured' => '0',
                    'widget_type_id' => '0',
                    'read_icon_class' => '',
                    'read_more_text' => '',
                    'sort_order' => '0',
                    'sort' => '0',
                    'page_size' => '10',
                    'template' => 'post',
                    'redirect_url' => '',
                    'status' => '1',
                    'created_at' => '0',
                    'updated_at' => '0',
                ]
            ]
        );
    }
}
