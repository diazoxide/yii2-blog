<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\widgets\ListView;

\diazoxide\blog\assets\AppAsset::register($this);

$this->title = $title;

Yii::$app->view->registerMetaTag([
    'name' => 'description',
    'content' => Yii::$app->name . ' ' . Module::t('blog', 'Blog')
]);
Yii::$app->view->registerMetaTag([
    'name' => 'keywords',
    'content' => Yii::$app->name . ', ' . Module::t('blog', 'Blog')
]);

if (Yii::$app->get('opengraph', false)) {
    Yii::$app->opengraph->set([
        'title' => $this->title,
        'description' => Module::t('blog', 'Blog'),
        //'image' => '',
    ]);
}


?>

<div class="blog-index">

    <div class="blog-index__header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="title title--1"><?= $title ?></h1>
                </div>
            </div>
        
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_brief',
                    'itemOptions'=>[
                            'class'=>'col-sm-3 top-buffer-20'
                    ],
                    'layout' => '{items}{pager}{summary}'
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


