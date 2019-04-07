<?php

use diazoxide\blog\Module;
use yii\helpers\Html;
$this->title = Module::t('', "Blog");
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-3">
    <?php
    $model = \diazoxide\blog\models\BlogPost::find()->limit(10)->orderBy(['id' => SORT_DESC]);
    echo Html::a('<i class="fa fa-newspaper-o"></i> ' . Module::t('', 'Blog Posts') . ' (' . $model->count() . ')', ['/blog/blog-post']);
    ?>
    <div class="top-buffer-20-xs">
        <?= \diazoxide\blog\widgets\Feed::widget([
            'items_count' => 20,
            'show_item_brief' => false,
            'show_title' => false,
            'item_brief_length' => 50,
            'infinite_scroll' => true,
            'id' => 'home_feed_widget',
            'item_image_type' => 'xsthumb',
            'item_title_length' => 70,
            'list_options' => ['style' => 'height:50vh; overflow:auto;'],
            'title_options' => ['class' => 'widget_title'],
            'show_item_category'=>true,
            'item_title_options' => ['class' => 'top-buffer-10-xs'],
            'item_info_container_options' => ['class' => 'text-right text-warning small'],
            'item_image_container_options' => ['class' => 'col-xs-2 left-padding-0-xs right-padding-10-xs'],
            'item_content_container_options' => ['class' => 'col-xs-10 nospaces-xs'],
            'item_options' => ['tag' => 'article', 'class' => 'item col-xs-12 top-buffer-10-xs left-padding-0-xs right-padding-10-xs'],
        ]);
        ?>
    </div>
    <div class="top-buffer-20-xs">
        <?= Html::a(Module::t('', 'See All'), ['/blog/blog-post'], ['class' => 'btn btn-warning']); ?>
    </div>
</div>

<div class="col-md-3">
    <?php
    $model = \diazoxide\blog\models\BlogCategory::find()->limit(10)->orderBy(['sort_order' => SORT_DESC]);
    echo Html::a('<i class="fa fa-list"></i> ' . Module::t('', 'Blog Categories') . ' (' . $model->count() . ')', ['/blog/blog-post']);
    ?>
    <ul>
        <?php
        /** @var \diazoxide\blog\models\BlogCategory $item */
        foreach ($model->all() as $item) {
            echo Html::tag(
                'li', Html::a($item->titleWithIcon, $item->url),
                ['class' => '']
            );
        }
        ?>
    </ul>
    <?= Html::a(Module::t('', 'See All'), ['/blog/blog-category'], ['class' => 'btn btn-warning']); ?>

</div>
<div class="col-md-3">
    <i class="fa fa-comment"></i>
    <?= Html::a(Module::t('', 'Blog Comments'), ['/blog/blog-comment']); ?>
</div>
<div class="col-md-3">
    <i class="fa fa-tags"></i>
    <?= Html::a(Module::t('', 'Blog Tags'), ['/blog/blog-tag']); ?>
</div>
