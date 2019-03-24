<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\widgets\ListView;
use \yii\widgets\Pjax;

\diazoxide\blog\assets\AppAsset::register($this);

$this->title = $title;

Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Yii::$app->name . ' ' . Module::t('Blog')
]);
Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Yii::$app->name . ', ' . Module::t('Blog')
]);

if (Yii::$app->get('opengraph', false)) {
    Yii::$app->opengraph->set([
        'title' => $this->title,
        'description' => Module::t('Blog'),
        //'image' => '',
    ]);
}


?>

<div class="blog-index">

    <div class="row">
        <div class="col-md-12">
            <h1 class="title title--1"><?= $title ?></h1>
        </div>
    </div>

    <?php
    Pjax::begin();
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_post',
        'itemOptions' => [
            'class' => 'row top-buffer-20-xs'
        ],
        'layout' => '{items}{pager}{summary}'
    ]);
    Pjax::end();
    ?>
</div>


