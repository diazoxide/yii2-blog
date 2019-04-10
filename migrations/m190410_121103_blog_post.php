<?php

use yii\db\Schema;
use yii\db\Migration;

class m190410_121103_blog_post extends Migration
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
            '{{%blog_post}}',
            [
                'id'=> $this->primaryKey(11),
                'title'=> $this->string(255)->notNull(),
                'content'=> $this->text()->notNull(),
                'brief'=> $this->text()->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
                'published_at'=> $this->integer(11)->notNull(),
                'permalink'=> $this->text()->notNull(),
                'banner'=> $this->string(255)->null()->defaultValue(null),
                'image_url'=> $this->text()->notNull(),
                'category_name'=> $this->string(255)->notNull(),
                'user_id'=> $this->integer(11)->null()->defaultValue(null),
                'slug'=> $this->string(128)->notNull(),
                'tags'=> $this->string(255)->notNull(),
                'click'=> $this->integer(11)->notNull()->defaultValue(0),
                'show_comments'=> $this->tinyInteger(1)->null()->defaultValue(null),
                'status'=> $this->integer(11)->notNull()->defaultValue(1),
                'updated_at'=> $this->integer(11)->notNull(),
                'category_id'=> $this->integer(11)->notNull(),
                'is_slide'=> $this->tinyInteger(1)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('slug','{{%blog_post}}',['slug'],true);
        $this->createIndex('category_id','{{%blog_post}}',['category_id'],false);
        $this->createIndex('status','{{%blog_post}}',['status'],false);
        $this->createIndex('created_at','{{%blog_post}}',['created_at'],false);
        $this->createIndex('updated_at','{{%blog_post}}',['updated_at'],false);
        $this->createIndex('published_at','{{%blog_post}}',['published_at'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('slug', '{{%blog_post}}');
        $this->dropIndex('category_id', '{{%blog_post}}');
        $this->dropIndex('status', '{{%blog_post}}');
        $this->dropIndex('created_at', '{{%blog_post}}');
        $this->dropIndex('updated_at', '{{%blog_post}}');
        $this->dropIndex('published_at', '{{%blog_post}}');
        $this->dropTable('{{%blog_post}}');
    }
}
