<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use yii\base\NotSupportedException;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

class Navigation extends \yii\bootstrap\Widget
{
    public $vertical = false;

    public $type = 'article';

    protected $_type;

    /**
     * @throws NotSupportedException
     */
    public function init()
    {
        parent::init();

        $this->_type = BlogPostType::findOne(['name' => $this->type]);

        if (!$this->_type) {
            throw new NotSupportedException('Navigation widget: Post type not found.');
        }

        if ($this->vertical) {
            $this->buildNav();
            return;
        }

        NavBar::begin([
            'brandLabel' => Module::t('', 'Categories'),
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
     * @param BlogCategory $parent
     * @return array
     */
    private function buildItems($parent)
    {
        $items = [];
        foreach ($parent->getChildren()->where(['type_id' => $this->_type->id, 'is_nav' => true, 'status' => IActiveStatus::STATUS_ACTIVE])->all() as $child) {
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