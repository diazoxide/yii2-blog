<?php

use yii\db\Schema;
use yii\db\Migration;

class m000007_000001_blog_comment extends Migration
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

        $this->addForeignKey('fk_blog_comment_post_id',
            '{{%blog_comment}}','post_id',
            '{{%blog_post}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_comment_post_id', '{{%blog_comment}}');

        $this->dropIndex('post_id', '{{%blog_comment}}');
        $this->dropIndex('status', '{{%blog_comment}}');
        $this->dropIndex('created_at', '{{%blog_comment}}');
        $this->dropTable('{{%blog_comment}}');
    }
}
