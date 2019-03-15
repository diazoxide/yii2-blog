<?php

use yii\helpers\Html;
use \diazoxide\blog\widgets\Feed;

/** @var String $title */
/** @var array $banners */
$this->title = $title;
?>
    <div id="blog-container" class="container">
        <div class="row top-buffer-20-sm">

            <div class="col-md-4">

                <div class="home-feed nopadding-xs" id="home-feed-container">
                    <div id="home_feed" class="top-buffer-20-xs top-buffer-0-md">
                        <div class="widget_title"><i
                                    class="fa fa-newspaper-o"></i> <?= \diazoxide\blog\Module::t('News Feed') ?>
                        </div>
                        <?= Feed::widget([
                            'items_count' => 15,
                            'show_item_brief' => false,
                            'item_brief_length' => 50,
                            'infinite_scroll' => true,
                            'id' => 'home_feed_widget',
                            'item_image_type' => 'xsthumb',
                            'item_image_container_options' => ['class' => 'col-xs-2 nospaces-xs'],
                            'item_content_container_options' => ['class' => 'col-xs-10 nospaces-xs'],
                            'item_options' => ['tag' => 'article', 'class' => 'item col-xs-12 top-buffer-20-xs left-padding-0-xs right-padding-10-xs'],
                        ]);
                        ?>
                    </div>
                </div>

            </div>

            <div class="col-md-8 home-slider-container">

                <div class="row">
                    <div class="col-lg-10 nopadding-xs">
                        <div class="widget_title hidden-xs">
                            <i class="fa fa-star"></i> <?= \diazoxide\blog\Module::t('General') ?>
                        </div>
                        <?= \diazoxide\blog\widgets\Slider::widget(
                            ['itemsCount' => 5]
                        ) ?>
                    </div>
                    <div class="col-lg-2 nopadding-xs">
                        <?= isset($banners[0]) ? $banners[0] : \diazoxide\blog\Module::t("Insert banner code"); ?>
                    </div>
                </div>

                <!--Popular posts-->
                <div class="row top-buffer-20-xs home-white-content">
                    <div class="col-xs-12">
                        <?= \diazoxide\blog\models\BlogWidgetType::findOne(3)->widget ?>
                    </div>
                </div><!--Popular posts end-->

                <div class="row top-buffer-20-xs home-white-content">

                    <?php foreach ($featuredCategories->where(['widget_type_id' => 1, 'is_featured' => true])->limit(3)->all() as $category): ?>
                        <div class="col-md-4 home_posts_widget">

                            <?= $category->widget ?>

                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="row top-buffer-20-xs home-white-content">

                    <?php foreach ($featuredCategories->where(['widget_type_id' => 2, 'is_featured' => true])->limit(3)->all() as $category): ?>
                        <div class="col-xs-12">

                            <?= $category->widget ?>

                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="row top-buffer-20-xs home-white-content">
                    <?php foreach ($featuredCategories->where(['widget_type_id' => 1, 'is_featured' => true])->offset(3)->limit(3)->all() as $category): ?>
                        <div class="col-md-4 home_posts_widget">

                            <?= $category->widget ?>

                        </div>
                    <?php endforeach; ?>

                </div>

            </div>

        </div>
    </div>

<?php $this->registerJs("

var sidebar = new StickySidebar('#home-feed-container', {
    containerSelector: '#blog-container',
    innerWrapperSelector: '#home_feed',
    topSpacing: 0,
    bottomSpacing: 0,
    resizeSensor: true,
    minWidth: 991
});
function fixFeedHeight(){
      var titleHeight = $('#home_feed .widget_title').outerHeight();
      var winHeight = $(window).outerHeight();
      var widgetHeight = winHeight - titleHeight;
      var widget = $('#home_feed_widget > div');
      widget.height(widgetHeight);
}
$(window).on('load ready resize', function(){fixFeedHeight();sidebar.updateSticky();});
$(document).ready(function(){fixFeedHeight();sidebar.updateSticky();});
"); ?>