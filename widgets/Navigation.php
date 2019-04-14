<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

class Navigation extends \yii\bootstrap\Widget
{
    public $vertical = false;

    public function init()
    {
        parent::init();

        if ($this->vertical) {
            $this->buildNav();
            return;
        }

        NavBar::begin([
            'brandLabel' => 'Բաժիններ',
            'brandOptions' => [
                'class' => 'visible-xs'
            ],
            'innerContainerOptions' => ['class' => 'container nopadding-sm'],
            'containerOptions' => ['class' => 'nopadding-sm'],

            'options' => [
                'class' => 'navbar-default',
                'id' => $this->options['id'],

            ],
        ]);

        $this->buildNav();

        echo Search::widget([]);

        NavBar::end();
    }

    /**
     * @param $parent
     * @return array
     */
    private function buildItems($parent)
    {
        $items = [];
        foreach ($parent->children as $child) {
            $items[] = ['label' => $child->titleWithIcon, 'url' => $child->url, 'items' => $this->buildItems($child)];
        }
        return $items;
    }

    public function buildNav()
    {
        $class = 'navbar-nav navbar-left';
        if ($this->vertical) {
            $class = 'nav-pills nav-stacked';
        }

        $model = BlogCategory::findOne(1);

        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => $class],
            'items' => $this->buildItems($model)
        ]);
    }
}