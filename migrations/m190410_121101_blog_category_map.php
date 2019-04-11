<?php

use yii\db\Schema;
use yii\db\Migration;

class m190410_121101_blog_category_map extends Migration
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
            '{{%blog_category_map}}',
            [
                'post_id'=> $this->integer(11)->notNull(),
                'category_id'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_category_map}}');
    }
}
