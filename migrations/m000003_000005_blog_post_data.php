<?php

use yii\db\Schema;
use yii\db\Migration;

class m000003_000005_blog_post_data extends Migration
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
            '{{%blog_post_data}}',
            [
                'id' => $this->primaryKey(11),
                'owner_id' => $this->integer(11)->notNull()->defaultValue(0),
                'name' => $this->string(191)->notNull(),
                'value' => $this->text(),
            ], $tableOptions
        );

        $this->createIndex('owner_id', '{{%blog_post_data}}', ['owner_id'], false);

        $this->addForeignKey('fk_blog_post_data_owner_id',
            '{{%blog_post_data}}', 'owner_id',
            '{{%blog_post}}', 'id',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blog_post_data_owner_id', '{{%blog_post_data}}');
        $this->dropIndex('owner_id', '{{%blog_post_data}}');
        $this->dropTable('{{%blog_post_data}}');
    }
}
