<?php

use yii\db\Schema;
use yii\db\Migration;

class m000001_000001_blog_post_type extends Migration
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
            '{{%blog_post_type}}',
            [
                'id'              => $this->primaryKey(11),
                'name'            => $this->string(64)->notNull(),
                'title'           => $this->string(191)->notNull(),
                'url_pattern'     => $this->string(191)->null(),
                'layout'          => $this->string(191)->null()->defaultValue(null),
                'single_pattern'  => $this->text()->null()->defaultValue(null),
                'archive_pattern' => $this->text()->null()->defaultValue(null),
                'default_pattern' => $this->text()->null()->defaultValue(null),
                'has_title'       => $this->boolean(),
                'has_content'     => $this->boolean(),
                'has_brief'       => $this->boolean(),
                'has_comment'     => $this->boolean(),
                'has_banner'      => $this->boolean(),
                'has_category'    => $this->boolean(),
                'has_tag'         => $this->boolean(),
                'has_book'        => $this->boolean(),
                'locked'          => $this->boolean(),
            ], $tableOptions
        );

        $this->insertDefaultContent();
    }

    public function safeDown()
    {
        $this->dropTable('{{%blog_post_type}}');
    }

    public function insertDefaultContent()
    {
        $this->batchInsert('{{%blog_post_type}}',
            [
                "id",
                "name",
                "title",
                "url_pattern",
                "layout",
                "single_pattern",
                "archive_pattern",
                "default_pattern",
                "has_title",
                "has_content",
                "has_brief",
                "has_comment",
                "has_banner",
                "has_category",
                "has_tag",
                "has_book",
                "locked"
            ],
            [
                [
                    'id'              => '1',
                    'name'            => 'article',
                    'title'           => 'Article',
                    'url_pattern'     => '<year:\\d{4}>/<month:\\d{2}>/<day:\\d{2}>/<slug>',
                    'layout'          => '',
                    'single_pattern'  => '<div id="blog-container">
    <div class="col-md-8 col-md-push-4 home-slider-container">
        <div class="nopadding-xs">
            [@vendor/diazoxide/yii2-blog/views/frontend/default/_article `{"post":"$post","showDate":"$showDate","dateType":"$dateType","showClicks":"$showClicks"}`]
        </div>
    </div>
    <div class="col-md-4 col-md-pull-8 nospaces-xs">
        <div class="home-feed nopadding-xs" id="home-feed-container">
            <div id="home_feed" class="top-buffer-20-xs top-buffer-0-md" style="height: 100vh;">
                [diazoxide\\blog\\models\\BlogWidgetType::widget `[9,{}]`]
            </div>
        </div>
    </div>
</div>',
                    'archive_pattern' => '<div id="blog-container">
    <div class="col-md-8 col-md-push-4 home-slider-container">
        <div class="nopadding-xs">
            [```return yii\\widgets\\ListView::widget([ \'dataProvider\' => $dependencies[\'dataProvider\'], \'itemView\' => \'@vendor/diazoxide/yii2-blog/views/frontend/default/_post\', \'itemOptions\' => [ \'class\' => \'row top-buffer-20-xs\' ], \'layout\' => \'{items}{pager}{summary}\' ]);```]
        </div>
    </div>
    <div class="col-md-4 col-md-pull-8 nospaces-xs">
        <div class="home-feed nopadding-xs" id="home-feed-container">
            <div id="home_feed" class="top-buffer-20-xs top-buffer-0-md" style="height: 100vh;">
                [diazoxide\\blog\\models\\BlogWidgetType::widget `[10,{}]`]
            </div>
        </div>
    </div>
</div>',
                    'default_pattern' => '<div id="blog-container">
    <div class="col-md-8 col-md-push-4 home-slider-container">
        <div class="row">
            <div class="nopadding-xs">
		        [diazoxide\\blog\\widgets\\Slider2::widget `[{"itemsCount":10}]`]
            </div>
        </div>
        <div class="nopadding-xs">
            [diazoxide\\blog\\models\\BlogWidgetType::widget `[3,{}]`]
        </div>
    </div>
    <div class="col-md-4 col-md-pull-8 nospaces-xs">
        <div class="home-feed nopadding-xs" id="home-feed-container">
            <div id="home_feed" class="top-buffer-20-xs top-buffer-0-md" style="height: 100vh;">
                [diazoxide\\blog\\models\\BlogWidgetType::widget `[8,{}]`]
            </div>
        </div>
    </div>
</div>',
                    'has_title'       => '1',
                    'has_content'     => '1',
                    'has_brief'       => '1',
                    'has_comment'     => '1',
                    'has_banner'      => '1',
                    'has_category'    => '1',
                    'has_tag'         => '1',
                    'has_book'        => '0',
                    'locked'          => '1',
                ],
                [
                    'id'              => '2',
                    'name'            => 'page',
                    'title'           => 'Page',
                    'url_pattern'     => 'page/<slug>',
                    'layout'          => '',
                    'single_pattern'  => '<div id="blog-container">
    <div class="col-xs-12">
        <div class="nopadding-xs">
            [@vendor/diazoxide/yii2-blog/views/frontend/default/_article `{"post":"$post","showDate":"$showDate","dateType":"$dateType","showClicks":"$showClicks"}`]
        </div>
    </div>
</div>',
                    'archive_pattern' => '',
                    'default_pattern' => '',
                    'has_title'       => '1',
                    'has_content'     => '1',
                    'has_brief'       => '1',
                    'has_comment'     => '0',
                    'has_banner'      => '0',
                    'has_category'    => '0',
                    'has_tag'         => '0',
                    'has_book'        => '0',
                    'locked'          => '1',
                ],
            ]
        );
    }
}
