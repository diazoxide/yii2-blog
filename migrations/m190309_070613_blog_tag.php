<?php

use yii\db\Schema;
use yii\db\Migration;

class m190309_070613_blog_tag extends Migration
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
            '{{%blog_tag}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(128)->notNull(),
                'frequency'=> $this->integer(11)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('frequency','{{%blog_tag}}',['frequency'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('frequency', '{{%blog_tag}}');
        $this->dropTable('{{%blog_tag}}');
    }
}
