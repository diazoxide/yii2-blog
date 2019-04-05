<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */
/* @var $this \yii\web\View */
/* @var $post \diazoxide\blog\models\BlogPost */

/* @var $dataProvider \yii\data\ActiveDataProvider */

use diazoxide\blog\Module;
use yii\base\Event;
use yii\helpers\Html;
use kartik\social\FacebookPlugin;

\diazoxide\blog\assets\AppAsset::register($this);

$this->title = $post->title;


$this->params['breadcrumbs'] = $post->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row blog-post__wrapper">
    <?= $this->render('_article', [
        'post' => $post,
        'dataProvider' => $dataProvider,
        'showDate' => $showDate,
        'dateType' => $dateType,
        'showClicks' => $showClicks,
    ]) ?>
</div>


<?php if ($post->module->enableShareButtons) : ?>
    <section id="share-box">
        <div class="row">
            <?php if ($post->module->addthisId) : ?>
                <script type="text/javascript"
                        src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $post->module->addthisId; ?>"></script>
                <div class="addthis_inline_share_toolbox_hty0"></div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>


<?php if ($post->module->enableComments && $post->show_comments) : ?>
    <section id="comments" class="top-buffer-20-xs">

        <div class="row">
            <div class="widget_title"><?= Module::t('Comments'); ?></div>

            <?php if ($post->module->enableFacebookComments): ?>

                <div class="col-sm-12">
                    <?= FacebookPlugin::widget(
                        ['type' => FacebookPlugin::COMMENT, 'settings' => [/*'data-width' => '100%',*/
                            'width' => '100%', 'data-numposts' => 5]]
                    ) ?>
                </div>

            <?php endif; ?>

            <?php if ($post->module->enableLocalComments) : ?>

                <div class="col-sm-12">
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_comment',
                        'viewParams' => [
                            'post' => $post
                        ],
                    ]) ?>
                </div>

                <div class="col-sm-12">
                    <h3><?= Module::t('Write comments'); ?></h3>
                    <?= $this->render('_form', [
                        'model' => $comment,
                    ]); ?>
                </div>

            <?php endif; ?>
        </div>

    </section>
<?php endif; ?>

<section id="related-posts">
    <?= \diazoxide\blog\widgets\Feed::widget([
        'items_count' => 3,
        'options' => ['tag' => 'div', 'class' => ''],
        'header_options' => ['tag' => 'div', 'class' => 'row'],
        'category_id' => $post->category_id,
        'show_title' => true,
        'title_options' => ['class' => 'widget_title'],
        'title' => Module::t('Related Posts'),
        'show_item_brief' => false,
        'body_options' => ['class' => 'row'],
        'show_item_category_icon' => false,
//        'infinite_scroll' => true,
//        'infinite_scroll_element_scroll' => false,
        'item_brief_length' => 50,
        'item_options' => ['class' => 'col-md-4 col-xs-12 top-buffer-20-xs'],
        'item_image_container_options' => ['class' => 'col-xs-4 col-md-12'],
        'item_content_container_options' => ['class' => 'col-xs-8 col-md-12'],
        'item_date_type' => 'dateTime',
        'item_info_container_options' => ['class' => 'text-warning text-right small'],
//            'item_date_options'=>['class'=>'text-right text-warning'],
        'id' => 'related_post_widget'
    ]);
    ?>
</section>

