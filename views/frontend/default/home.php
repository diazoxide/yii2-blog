<?php

use yii\helpers\Html;

/** @var String $title */
/** @var array $banners */
$this->title = $title;


?>
<div id="blog-container" class="container">
    <div class="row top-buffer-20-sm">

        <div class="col-md-8 home-slider-container">

            <div class="row">
                <div class="col-lg-10 nopadding">
                    <div class="widget_title hidden-xs"><i class="fa fa-star"></i> Գլխավոր</div>

                    <?= \diazoxide\blog\widgets\Slider::widget(
                        ['itemsCount' => 5]
                    ) ?>
                </div>
                <div class="col-lg-2 nopadding">
                    <?= isset($banners[0]) ? $banners[0] : \diazoxide\blog\Module::t('blog', "Insert Banner Code"); ?>
                </div>
            </div>


            <!--Popular posts-->
            <div class="row top-buffer-20 home-white-content">
                <div class="widget_title"><i class="fa fa-eye"></i> Շատ Ընթերցվող</div>
                <div class="col-md-6">
                    <div class="top-buffer-20">
                        <?= \diazoxide\blog\widgets\Posts::widget([
                            'itemsCount' => 1,
                            'type' => 'hot',
                            'daysInterval' => 7,
                            'briefLength' => 200,
                            'showItemReadMoreButton' => true,
                            'showCategoryTitle' => true,
                            'showItemViews' => true,
                            'itemImageType' => 'xthumb'
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="top-buffer-20">
                        <?= \diazoxide\blog\widgets\Feed::widget([
                            'itemsCount' => 6,
                            'showBrief' => false,
                            'showPager' => false,
                            'id' => 'home-page-hot-posts',
                            'type' => 'hot',
                            'showItemViews' => true,
                            'showItemCategory' => false,
                            'daysInterval' => 7,
                            'offset' => 1
                            //'loadMoreButton'=>true,
                        ]);
                        ?>
                    </div>
                </div>


            </div><!--Popular posts end-->

            <div class="row top-buffer-20 home-white-content">
                <div class="col-md-4 home_posts_widget">

                    <?= \diazoxide\blog\widgets\Posts::widget([
                        'itemsCount' => 2,
                        'categoryId' => 110,
                        'showCategoryTitle' => true
                    ]) ?>
                </div>

                <div class="col-md-4 home_posts_widget">
                    <?= \diazoxide\blog\widgets\Posts::widget([
                        'itemsCount' => 2,
                        'categoryId' => 114,
                        'showCategoryTitle' => true
                    ]) ?>
                </div>

                <div class="col-md-4 home_posts_widget">
                    <?= \diazoxide\blog\widgets\Posts::widget([
                        'itemsCount' => 2,
                        'categoryId' => 103,
                        'showCategoryTitle' => true
                    ]) ?>
                </div>


            </div>

            <!--Popular posts-->
            <div class="row top-buffer-20 home-videos-content">

                <div class="widget_title"><i class="fa fa-youtube-play"></i> Տեսանյութեր</div>

                <div class="col-md-6">
                    <div class="top-buffer-20">
                        <?= \diazoxide\blog\widgets\Posts::widget([
                            'itemsCount' => 1,
                            'categoryId' => 139,
                            'briefLength' => 200,
                            'showItemReadMoreButton' => true,
                            'showCategoryTitle' => false,
                            'itemImageType' => 'xthumb',
                            'itemReadMoreText' => '<i class="fa fa-play"></i> ' . \diazoxide\blog\Module::t('blog', 'Play video'),
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="top-buffer-20">
                        <?= \diazoxide\blog\widgets\Feed::widget([
                            'itemsCount' => 5,
                            'categoryId' => 139,
                            'showBrief' => false,
                            'showPager' => false,
                            'id' => 'home-page-hot-posts',
                            'showItemCategory' => false,
                            'offset' => 1
                            //'loadMoreButton'=>true,
                        ]);
                        ?>
                    </div>
                </div>

            </div><!--Popular posts end-->

            <div class="row top-buffer-20 home-white-content">
                <div class="col-md-4 home_posts_widget">
                    <?= \diazoxide\blog\widgets\Posts::widget([
                        'itemsCount' => 2,
                        'categoryId' => 107,
                        'showCategoryTitle' => true
                    ]) ?>
                </div>

                <div class="col-md-4 home_posts_widget">
                    <?= \diazoxide\blog\widgets\Posts::widget([
                        'itemsCount' => 2,
                        'categoryId' => 140,
                        'showCategoryTitle' => true
                    ]) ?>
                </div>

                <div class="col-md-4 home_posts_widget">
                    <?= \diazoxide\blog\widgets\Posts::widget([
                        'itemsCount' => 2,
                        'categoryId' => 100,
                        'showCategoryTitle' => true
                    ]) ?>
                </div>


            </div>

        </div>


        <div class="col-md-4">
            <div class="home-feed nopadding" id="home-feed-container">
                <div id="home_feed" class="top-buffer-20-xs top-buffer-0-md">

                    <div id="home_feed_ad" class="visible-lg visible-md">
                        <?php
                        if (isset($banners[1])) {
                            $banner = $banners[1];
                            echo Html::a(Html::img($banner['src'], ['class' => 'img-responsive']), $banner['href']);
                        } else {
                            echo \diazoxide\blog\Module::t('blog', "Insert Banner Code");
                        }
                        ?>
                    </div>
                    <div class="widget_title"><i class="fa fa-newspaper-o"></i> Լրահոս</div>
                    <?= \diazoxide\blog\widgets\Feed::widget([
                        'itemsCount' => 15,
                        'showBrief' => false,
                        //'showItemCategoryIcon' => 'true',
                        'briefLength' => 50,
                        'infiniteScroll' => true,
                        'id' => 'home_feed_widget',
                        'itemImageType' => 'xsthumb',
                        'itemImageContainerOptions' => ['class' => 'col-xs-2 nospaces'],
                        'itemContentContainerOptions' => ['class' => 'col-xs-10 nospaces'],
                        'articleOptions' => ['tag' => 'article', 'class' => 'item col-xs-12 top-buffer-20-xs left-padding-0-xs right-padding-10-xs'],
                    ]);
                    ?>
                </div>
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
 var titleHeight = $('#home_feed_ad').height();
      var adBarHeight = $('#home_feed .widget_title').outerHeight();
      var winHeight = $(window).outerHeight();
      var widgetHeight = winHeight - titleHeight - adBarHeight;
      var widget = $('#home_feed .feed-widget-listview');
      widget.height(widgetHeight);
}

$(window).on('load ready resize', fixFeedHeight);
$(document).ready(fixFeedHeight);

"); ?>