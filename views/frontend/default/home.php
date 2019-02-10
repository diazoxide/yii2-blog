<?php

$this->title = Yii::$app->params['home_title'];
?>

<div class="row">
    <div class="col-sm-8 home-slider-container">

        <div class="widget_title">Գլխավոր</div>

        <?= \app\modules\blog\widgets\Slider::widget(
                ['itemsCount'=>5]
        ) ?>

        <div class="row">
            <div class="col-sm-4 home_posts_widget">

                <?= \app\modules\blog\widgets\Posts::widget([
                    'itemsCount' => 2,
                    'categoryId' => 110,
                    'showCategoryTitle' => true
                ]) ?>
            </div>

            <div class="col-sm-4 home_posts_widget">
                <?= \app\modules\blog\widgets\Posts::widget([
                    'itemsCount' => 2,
                    'categoryId' => 114,
                    'showCategoryTitle' => true
                ]) ?>
            </div>

            <div class="col-sm-4 home_posts_widget">
                <?= \app\modules\blog\widgets\Posts::widget([
                    'itemsCount' => 2,
                    'categoryId' => 103,
                    'showCategoryTitle' => true
                ]) ?>
            </div>


        </div>

        <div class="row">
            <div class="col-sm-4 home_posts_widget">
                <?= \app\modules\blog\widgets\Posts::widget([
                    'itemsCount' => 2,
                    'categoryId' => 107,
                    'showCategoryTitle' => true
                ]) ?>
            </div>

            <div class="col-sm-4 home_posts_widget">
                <?= \app\modules\blog\widgets\Posts::widget([
                    'itemsCount' => 2,
                    'categoryId' => 140,
                    'showCategoryTitle' => true
                ]) ?>
            </div>

            <div class="col-sm-4 home_posts_widget">
                <?= \app\modules\blog\widgets\Posts::widget([
                    'itemsCount' => 2,
                    'categoryId' => 100,
                    'showCategoryTitle' => true
                ]) ?>
            </div>


        </div>

    </div>
    <div class="col-sm-4 home-feed nopadding" id="home-feed-container">
        <div id="home_feed">
            <div class="widget_title">Լրահոս</div>
            <?= \app\modules\blog\widgets\Feed::widget([
                'itemsCount' => 15,
                'showBrief' => false,
                'briefLength' => 50,
                'infiniteScroll' => true,
                'id' => 'home_feed_widget'
            ]);
            ?>
        </div>

    </div>

    <?php $script = <<< JS
        jQuery('#home-feed-container').stickySidebar({
            topSpacing: 0,
            bottomSpacing: 0,
            resizeSensor: true,
            containerSelector: '#main-content .container',
            innerWrapperSelector: '#home_feed',
            minWidth: 765,

        });
JS;
    $this->registerJs($script);
    ?>


</div>
