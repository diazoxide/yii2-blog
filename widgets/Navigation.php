<?php
namespace app\modules\blog\widgets;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

class Navigation extends \yii\bootstrap\Widget
{
    public function init()
    {
        parent::init();
        NavBar::begin([
            'options' => [
                'class' => 'navbar-default',
                'id'=>$this->options['id'],

            ],
        ]);
        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => \app\modules\blog\models\BlogCategory::getAllMenuItems()
        ]);
        NavBar::end();
    }
}