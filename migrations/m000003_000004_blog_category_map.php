<?php

use yii\db\Schema;
use yii\db\Migration;

class m000003_000004_blog_category_map extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

        $this->createTable(
            '{{%blog_category_map}}',
            [
                'post_id'=> $this->integer(11)->notNull(),
                'category_id'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );

        $this->addForeignKey('fk_blog_category_map_category_id',
            '{{%blog_category_map}}', 'category_id',
            '{{%blog_category}}', 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey('fk_blog_category_map_post_id',
            '{{%blog_category_map}}', 'post_id',
            '{{%blog_post}}', 'id',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_category_map_category_id', '{{%blog_category_map}}');

        $this->dropForeignKey('fk_blog_category_map_post_id', '{{%blog_category_map}}');

        $this->dropTable('{{%blog_category_map}}');
    }
}
