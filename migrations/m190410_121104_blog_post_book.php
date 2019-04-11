<?php

use yii\db\Schema;
use yii\db\Migration;

class m190410_121104_blog_post_book extends Migration
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
            '{{%blog_post_book}}',
            [
                'id'=> $this->primaryKey(11),
                'post_id'=> $this->integer(11)->notNull(),
                'title'=> $this->string(255)->notNull(),
                'brief'=> $this->string(255)->notNull(),
                'banner'=> $this->string(255)->notNull(),
                'slug'=> $this->string(128)->notNull(),
                'bbcode'=> $this->tinyInteger(1)->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->notNull(),
                'status'=> $this->tinyInteger(1)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('post_id','{{%blog_post_book}}',['post_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('post_id', '{{%blog_post_book}}');
        $this->dropTable('{{%blog_post_book}}');
    }
}
