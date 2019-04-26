<?php

use yii\db\Schema;
use yii\db\Migration;

class m000002_000001_blog_category extends Migration
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
            '{{%blog_category}}',
            [
                'id' => $this->primaryKey(11),
                'parent_id' => $this->integer(11)->notNull()->defaultValue(0),
                'title' => $this->string(255)->notNull(),
                'slug' => $this->string(128)->notNull(),
                'banner' => $this->string(255)->null()->defaultValue(null),
                'icon_class' => $this->string(60)->null()->defaultValue(null),
                'is_nav' => $this->integer(11)->notNull()->defaultValue(1),
                'is_featured' => $this->tinyInteger(1)->defaultValue(0),
                'widget_type_id' => $this->integer(11)->null()->defaultValue(null),
                'read_icon_class' => $this->string(60)->null(),
                'read_more_text' => $this->string(60)->null(),
                'sort_order' => $this->integer(11)->notNull()->defaultValue(50),
                'sort' => $this->integer(11)->notNull()->defaultValue(0),
                'page_size' => $this->integer(11)->notNull()->defaultValue(10),
                'template' => $this->string(255)->notNull()->defaultValue('post'),
                'redirect_url' => $this->string(255)->null()->defaultValue(null),
                'status' => $this->integer(11)->notNull()->defaultValue(1),
                'created_at' => $this->integer(11)->notNull()->defaultValue(0),
                'updated_at' => $this->integer(11)->notNull()->defaultValue(0),
                'type_id' => $this->integer(11)->null()->defaultValue(1),
            ], $tableOptions
        );
        $this->createIndex('is_nav', '{{%blog_category}}', ['is_nav'], false);
        $this->createIndex('sort_order', '{{%blog_category}}', ['sort_order'], false);
        $this->createIndex('status', '{{%blog_category}}', ['status'], false);
        $this->createIndex('created_at', '{{%blog_category}}', ['created_at'], false);

        $this->addForeignKey('fk_blog_category_type_id',
            '{{%blog_category}}', 'type_id',
            '{{%blog_post_type}}', 'id',
            'CASCADE', 'CASCADE'
        );

        $this->insertDefaultContent();
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_category_type_id', '{{%blog_category}}');

        $this->dropIndex('is_nav', '{{%blog_category}}');
        $this->dropIndex('sort_order', '{{%blog_category}}');
        $this->dropIndex('status', '{{%blog_category}}');
        $this->dropIndex('created_at', '{{%blog_category}}');
        $this->dropTable('{{%blog_category}}');
    }

    public function insertDefaultContent()
    {
        $this->batchInsert('{{%blog_category}}',
            ["id", "type_id", "parent_id", "title", "slug"],
            [
                [
                    'id' => '1',
                    'type_id'=>null,
                    'parent_id' => '0',
                    'title' => 'Main',
                    'slug' => 'main',
                ],
                [
                    'id' => '2',
                    'type_id'=>'1',
                    'parent_id' => '1',
                    'title' => 'My Articles',
                    'slug' => 'my-articles',
                ]
            ]
        );
    }
}
