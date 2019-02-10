<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

use app\modules\blog\Module;
use yii\widgets\ListView;

\app\modules\blog\assets\AppAsset::register($this);

$this->title = Module::t('blog', 'Blog');
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
//$this->params['breadcrumbs'][] = '文章';

/*$this->breadcrumbs=[
    //$post->category->title => Yii::app()->createUrl('post/category', array('id'=>$post->category->id, 'slug'=>$post->category->slug)),
    '文章',
];*/

?>

<div class="blog-index">

    <div class="blog-index__header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="title title--1"><?= Module::t('blog', 'Blog'); ?></h1>
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
                    'options' => [
                        'class' => 'blog-list-view'
                    ],
                    'layout' => '{items}{pager}{summary}'
                ]);
                ?>
            </div>
        </div>
    </div>
</div>


