<?php

use yii\db\Schema;
use yii\db\Migration;

class m000002_000004_blog_category_data extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

        $this->createTable(
            '{{%blog_category_data}}',
            [
                'id' => $this->primaryKey(11),
                'owner_id' => $this->integer(11)->notNull()->defaultValue(0),
                'name' => $this->string(255)->notNull(),
                'value' => $this->text(),
            ], $tableOptions
        );

        $this->createIndex('owner_id', '{{%blog_category_data}}', ['owner_id'], false);

        $this->addForeignKey('fk_blog_category_data_owner_id',
            '{{%blog_category_data}}', 'owner_id',
            '{{%blog_category}}', 'id',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_category_data_owner_id', '{{%blog_category_data}}');
        $this->dropIndex('owner_id', '{{%blog_category_data}}');
        $this->dropTable('{{%blog_category_data}}');
    }
}
