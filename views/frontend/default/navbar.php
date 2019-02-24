<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([

    'options' => [
        'class' => 'navbar-default',
        'id'=>'blog_navbar'
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => \diazoxide\blog\models\BlogCategory::getAllMenuItems()
]);


NavBar::end();
?>