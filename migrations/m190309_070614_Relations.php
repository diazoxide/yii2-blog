<?php

use yii\db\Schema;
use yii\db\Migration;

class m190309_070614_Relations extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
//        $this->addForeignKey('fk_blog_comment_post_id',
//            '{{%blog_comment}}', 'post_id',
//            '{{%blog_post}}', 'id',
//            'CASCADE', 'CASCADE'
//        );
//        $this->addForeignKey('fk_blog_post_category_id',
//            '{{%blog_post}}', 'category_id',
//            '{{%blog_category}}', 'id',
//            'CASCADE', 'CASCADE'
//        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_comment_post_id', '{{%blog_comment}}');
        $this->dropForeignKey('fk_blog_post_category_id', '{{%blog_post}}');
    }
}
