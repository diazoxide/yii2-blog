<?php
namespace diazoxide\yii2blog\widgets;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

class Navigation extends \yii\bootstrap\Widget
{
    public $vertical = false;

    public function init()
    {
        parent::init();

        if($this->vertical){
            $this->buildNav();
            return;
        }

        NavBar::begin([
            'brandLabel'=>'Բաժիններ',
            'brandOptions'=>[
                'class'=>'visible-xs'
            ],
            'innerContainerOptions' => ['class' => 'container nopadding-sm'],
            'containerOptions' => [ 'class'=>'nopadding-sm'],

            'options' => [
                'class' => 'navbar-default',
                'id'=>$this->options['id'],

            ],
        ]);

        $this->buildNav();

        echo \diazoxide\yii2blog\widgets\Search::widget([]);

        NavBar::end();
    }
    public function buildNav(){
        $class = 'navbar-nav navbar-left';
        if($this->vertical){
            $class = 'nav-pills nav-stacked';
        }

        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => $class],
            'items' => \diazoxide\yii2blog\models\BlogCategory::getAllMenuItems()
        ]);
    }
}