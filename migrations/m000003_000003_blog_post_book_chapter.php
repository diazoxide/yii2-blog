<?php

use yii\db\Migration;

class m000003_000003_blog_post_book_chapter extends Migration
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
        $this->createIndex('book_id', '{{%blog_post_book_chapter}}', ['book_id'], false);
        $this->createIndex('parent_id', '{{%blog_post_book_chapter}}', ['book_id'], false);


        $this->addForeignKey('fk_blog_post_book_chapter_book_id',
            '{{%blog_post_book_chapter}}', 'book_id',
            '{{%blog_post_book}}', 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey('fk_blog_post_book_chapter_parent_id',
            '{{%blog_post_book_chapter}}', 'parent_id',
            '{{%blog_post_book_chapter}}', 'id',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_post_book_chapter_book_id', '{{%blog_post_book_chapter}}');

        $this->dropForeignKey('fk_blog_post_book_chapter_parent_id', '{{%blog_post_book_chapter}}');

        $this->dropIndex('book_id', '{{%blog_post_book}}');

        $this->dropIndex('parent_id', '{{%blog_post_book}}');


        $this->dropTable('{{%blog_post_book_chapter}}');
    }
}
