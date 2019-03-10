<?php

use yii\db\Schema;
use yii\db\Migration;

class m190309_070608_blog_category extends Migration
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
            '{{%blog_category}}',
            [
                'id'=> $this->primaryKey(11),
                'parent_id'=> $this->integer(11)->notNull()->defaultValue(0),
                'title'=> $this->string(255)->notNull(),
                'slug'=> $this->string(128)->notNull(),
                'icon_class'=> $this->string(255)->null()->defaultValue(null),
                'banner'=> $this->string(255)->null()->defaultValue(null),
                'is_nav'=> $this->integer(11)->notNull()->defaultValue(1),
                'is_featured'=> $this->tinyInteger(1)->notNull(),
                'sort_order'=> $this->integer(11)->notNull()->defaultValue(50),
                'page_size'=> $this->integer(11)->notNull()->defaultValue(10),
                'template'=> $this->string(255)->notNull()->defaultValue('post'),
                'redirect_url'=> $this->string(255)->null()->defaultValue(null),
                'status'=> $this->integer(11)->notNull()->defaultValue(1),
                'created_at'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('is_nav','{{%blog_category}}',['is_nav'],false);
        $this->createIndex('sort_order','{{%blog_category}}',['sort_order'],false);
        $this->createIndex('status','{{%blog_category}}',['status'],false);
        $this->createIndex('created_at','{{%blog_category}}',['created_at'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('is_nav', '{{%blog_category}}');
        $this->dropIndex('sort_order', '{{%blog_category}}');
        $this->dropIndex('status', '{{%blog_category}}');
        $this->dropIndex('created_at', '{{%blog_category}}');
        $this->dropTable('{{%blog_category}}');
    }
}
