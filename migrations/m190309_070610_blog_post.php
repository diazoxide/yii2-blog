<?php

use yii\db\Schema;
use yii\db\Migration;

class m190309_070610_blog_post extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        if (Yii::$app->db->getTableSchema('{{%blog_post}}',true)===null) {

            $this->createTable(
                '{{%blog_post}}',
                [
                    'id' => $this->primaryKey(11),
                    'category_id' => $this->integer(11)->notNull(),
                    'title' => $this->string(255)->notNull(),
                    'brief' => $this->text()->null()->defaultValue(null),
                    'content' => $this->text()->notNull(),
                    'tags' => $this->string(255)->notNull(),
                    'slug' => $this->string(128)->notNull(),
                    'banner' => $this->string(255)->null()->defaultValue(null),
                    'is_slide' => $this->integer(11)->notNull()->defaultValue(1),
                    'click' => $this->integer(11)->notNull()->defaultValue(0),
                    'user_id' => $this->integer(11)->null()->defaultValue(null),
                    'show_comments' => $this->tinyInteger(1)->notNull()->defaultValue(1),
                    'status' => $this->integer(11)->notNull()->defaultValue(1),
                    'created_at' => $this->integer(11)->notNull(),
                    'updated_at' => $this->integer(11)->notNull(),
                ], $tableOptions
            );
            $this->createIndex('category_id', '{{%blog_post}}', ['category_id'], false);
            $this->createIndex('is_nav', '{{%blog_post}}', ['is_slide'], false);
            $this->createIndex('status', '{{%blog_post}}', ['status'], false);
            $this->createIndex('created_at', '{{%blog_post}}', ['created_at'], false);
        }
    }

    public function safeDown()
    {
        $this->dropIndex('category_id', '{{%blog_post}}');
        $this->dropIndex('is_nav', '{{%blog_post}}');
        $this->dropIndex('status', '{{%blog_post}}');
        $this->dropIndex('created_at', '{{%blog_post}}');
        $this->dropTable('{{%blog_post}}');
    }
}
