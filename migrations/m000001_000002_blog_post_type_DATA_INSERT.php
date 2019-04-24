<?php

use yii\db\Schema;
use yii\db\Migration;

class m000001_000002_blog_post_type_DATA_INSERT extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%blog_post_type}}',
            ['id', 'title', 'url_pattern', 'has_title', 'has_content', 'has_brief', 'has_comment', 'has_banner', 'has_category', 'has_tag', 'has_book', 'locked','layout'],
            [
                [
                    'id' => '1',
                    'title' => 'Article',
                    'url_pattern' => '<year:\d{4}>/<month:\d{2}>/<day:\d{2}>/<slug>',
                    'has_title' => true,
                    'has_content' => true,
                    'has_brief' => true,
                    'has_comment' => true,
                    'has_banner' => true,
                    'has_category' => true,
                    'has_tag' => true,
                    'has_book' => false,
                    'locked' => true,
                    'layout'=>null,
                ],
                [
                    'id' => '2',
                    'title' => 'Page',
                    'url_pattern' => 'page/<slug>',
                    'has_title' => true,
                    'has_content' => true,
                    'has_brief' => true,
                    'has_comment' => false,
                    'has_banner' => false,
                    'has_category' => false,
                    'has_tag' => false,
                    'has_book' => false,
                    'locked' => true,
                    'layout'=>null,
                ]
            ]
        );

    }
}
