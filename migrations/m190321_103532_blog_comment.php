<?php

use yii\db\Schema;
use yii\db\Migration;

class m190321_103532_blog_comment extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%blog_comment}}',
            [
                'id'=> $this->primaryKey(11),
                'post_id'=> $this->integer(11)->notNull(),
                'content'=> $this->text()->notNull(),
                'author'=> $this->string(128)->notNull(),
                'email'=> $this->string(128)->notNull(),
                'url'=> $this->string(128)->null()->defaultValue(null),
                'status'=> $this->integer(11)->notNull()->defaultValue(1),
                'created_at'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('post_id','{{%blog_comment}}',['post_id'],false);
        $this->createIndex('status','{{%blog_comment}}',['status'],false);
        $this->createIndex('created_at','{{%blog_comment}}',['created_at'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('post_id', '{{%blog_comment}}');
        $this->dropIndex('status', '{{%blog_comment}}');
        $this->dropIndex('created_at', '{{%blog_comment}}');
        $this->dropTable('{{%blog_comment}}');
    }
}
