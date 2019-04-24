<?php

use yii\db\Schema;
use yii\db\Migration;

class m000009_000001_blog_widget_type extends Migration
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
            '{{%blog_widget_type}}',
            [
                'id'=> $this->primaryKey(11),
                'title'=> $this->string(255)->notNull(),
                'config'=> $this->text()->notNull(),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_widget_type}}');
    }
}
