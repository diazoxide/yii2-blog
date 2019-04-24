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
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable(
            '{{%blog_post_type}}',
            [
                'id' => $this->primaryKey(11),
                'name' => $this->string(64)->notNull(),
                'title' => $this->string(255)->notNull(),
                'url_pattern' => $this->string(255)->null(),
                'layout' => $this->string(255)->null()->defaultValue(null),
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

    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_post_type}}');
    }
}
