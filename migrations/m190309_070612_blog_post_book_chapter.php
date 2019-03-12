<?php

use yii\db\Schema;
use yii\db\Migration;

class m190309_070612_blog_post_book_chapter extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        if (Yii::$app->db->getTableSchema('{{%blog_post_book_chapter}}', true) === null) {

            $this->createTable(
                '{{%blog_post_book_chapter}}',
                [
                    'id' => $this->primaryKey(11),
                    'book_id' => $this->integer(11)->notNull(),
                    'title' => $this->string(255)->notNull(),
                    'content' => $this->text()->notNull(),
                    'bbcode' => $this->tinyInteger(1)->null()->defaultValue(null),
                    'brief' => $this->string(255)->notNull(),
                    'keywords' => $this->string(255)->notNull(),
                    'banner' => $this->string(255)->notNull(),
                    'parent_id' => $this->integer(11)->null()->defaultValue(null),
                    'sort_order' => $this->integer(11)->notNull(),
                ], $tableOptions
            );

        }
    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_post_book_chapter}}');
    }
}
