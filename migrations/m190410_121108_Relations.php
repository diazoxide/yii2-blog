<?php

use yii\db\Schema;
use yii\db\Migration;

class m190410_121108_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_blog_comment_post_id',
            '{{%blog_comment}}','post_id',
            '{{%blog_post}}','id',
            'CASCADE','CASCADE'
         );
        $this->addForeignKey('fk_blog_comment_post_id',
            '{{%blog_comment}}','post_id',
            '{{%blog_post}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_comment_post_id', '{{%blog_comment}}');
        $this->dropForeignKey('fk_blog_comment_post_id', '{{%blog_comment}}');
    }
}
