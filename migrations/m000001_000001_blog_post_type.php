<?php

use yii\db\Schema;
use yii\db\Migration;

class m000001_000001_blog_post_type extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

        $this->createTable(
            '{{%blog_post_type}}',
            [
                'id' => $this->primaryKey(11),
                'name' => $this->string(64)->notNull(),
                'title' => $this->string(255)->notNull(),
                'url_pattern' => $this->string(255)->null(),
                'layout' => $this->string(255)->null()->defaultValue(null),
                'single_pattern' => $this->text()->null()->defaultValue(null),
                'archive_pattern' => $this->text()->null()->defaultValue(null),
                'default_pattern' => $this->text()->null()->defaultValue(null),
                'has_title' => $this->boolean(),
                'has_content' => $this->boolean(),
                'has_brief' => $this->boolean(),
                'has_comment' => $this->boolean(),
                'has_banner' => $this->boolean(),
                'has_category' => $this->boolean(),
                'has_tag' => $this->boolean(),
                'has_book' => $this->boolean(),
                'locked' => $this->boolean(),
            ], $tableOptions
        );

        $this->insertDefaultContent();
    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_post_type}}');
    }

    public function insertDefaultContent()
    {
        $this->batchInsert('{{%blog_post_type}}',
            ['id', 'name', 'title', 'url_pattern', 'has_title', 'has_content', 'has_brief', 'has_comment', 'has_banner', 'has_category', 'has_tag', 'has_book', 'locked', 'layout'],
            [
                [
                    'id' => '1',
                    'name' => 'article',
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
                    'layout' => null,
                ],
                [
                    'id' => '2',
                    'name' => 'page',
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
                    'layout' => null,
                ]
            ]
        );

    }
}
