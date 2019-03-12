<?php

namespace diazoxide\blog\traits;

use diazoxide\blog\models\BlogWidgetType;
use diazoxide\blog\Module;
use diazoxide\blog\widgets\Feed;

trait FeedTrait
{
    /**
     * @return  \diazoxide\blog\Module
     */

    public $type = Feed::TYPE_RECENT;

    public $category_id;
    public $show_category_title = false;

    public $items_count = 10;
    public $offset = 0;
    public $item_image_type = 'mthumb';

    public $item_image_container_options = ['class' => 'col-xs-3'];
    public $item_content_container_options = ['class' => 'col-xs-9'];
    public $article_options = ['tag' => 'article', 'class' => 'item row top-buffer-20-xs'];
    public $list_options = ['tag' => 'div', 'class' => 'feed-widget-listview row'];

    public $show_brief = false;
    public $brief_length = 100;

    public $infinite_scroll = false;
    public $show_pager = false;
    public $load_more_button = false;

    public $show_item_category = false;
    public $show_item_category_icon = false;
    public $show_item_category_with_icon = false;
    public $show_item_views = false;
    public $show_item_date = true;

    public $days_interval = 0;


    public function getLabels()
    {
        return  [
            'config[title]' => Module::t('Title'),
            'config[show_category_title]' => Module::t('Show Category Title'),
        ];
    }

}
