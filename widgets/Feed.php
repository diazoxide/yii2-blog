<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use kop\y2sp\ScrollPager;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Html;
use yii\widgets\ListView;

class Feed extends \yii\bootstrap\Widget
{
    const TYPE_RANDOM = 'random';
    const TYPE_POPULAR = 'hot';
    const TYPE_RECENT = 'recent';

    public $categoryId;
    public $showCategoryTitle = false;

    public $type = self::TYPE_RECENT;
    public $itemsCount = 10;
    public $offset = 0;
    public $itemImageType = 'mthumb';

    public $itemImageContainerOptions = ['class' => 'col-xs-3'];
    public $itemContentContainerOptions = ['class' => 'col-xs-9'];
    public $articleOptions = ['tag'=>'article','class' => 'item row top-buffer-20-xs'];
    public $listOptions = ['tag'=>'div','class' => 'feed-widget-listview row'];


    public $showBrief = false;
    public $briefLength = 100;


    public $infiniteScroll = false;
    public $showPager = false;
    public $loadMoreButton = false;


    public $showItemCategory = false;
    public $showItemCategoryIcon = false;
    public $showItemCategoryWithIcon = false;
    public $showItemViews = false;
    public $showItemDate = true;

    public $daysInterval = 0;

    public function init()
    {
        parent::init();

        $listViewId = "Feed-widget-" . $this->id;

        $query = BlogPost::find();

        $query->where(['status' => IActiveStatus::STATUS_ACTIVE])
            ->orderBy($this->getOrderFromType());


        if ($this->daysInterval) {
            $query->andWhere('FROM_UNIXTIME(created_at) > NOW() - INTERVAL ' . $this->daysInterval . ' DAY');
        }

        if ($this->categoryId) {
            $category = BlogCategory::findOne($this->categoryId);
            if ($category) {
                if ($this->showCategoryTitle) {
                    echo Html::tag('div', $category->icon . ' ' . $category->title, ['class' => 'widget_title']);
                }
                $query->andWhere(['category_id' => $this->categoryId]);
            }

        }

        $pager = null;

        if ($this->infiniteScroll || $this->loadMoreButton) {
            $this->showPager = true;
            $pager = [
                'class' => ScrollPager::className(),
                'container' => "#$listViewId",
                'triggerText' => Module::t('blog','Load more...')
            ];
        }

        if (!$this->showPager) {
            $query->offset($this->offset);
            $query->limit($this->itemsCount);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $this->showPager ? [
                'pageSize' => $this->itemsCount,
                'pageParam' => $this->id . '_page',
                'pageSizeParam' => $this->id . '_page_size',
            ] : false
        ]);


        if ($this->infiniteScroll) {
            $pager['enabledExtensions'] = [ScrollPager::EXTENSION_SPINNER, ScrollPager::EXTENSION_NONE_LEFT, ScrollPager::EXTENSION_PAGING];
            $pager['overflowContainer'] = "#$listViewId";
        }
        $this->listOptions['id']=$listViewId;
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'id' => $listViewId,
                'class' => 'feed-widget-listview'
            ],
            'itemOptions' =>$this->articleOptions,
            'layout' => "{items}" . ($this->showPager ? "{pager}" : ""),
            'itemView' => '@vendor/diazoxide/yii2-blog/widgets/views/_feed_item',
            'viewParams' => [
                'showBrief' => $this->showBrief,
                'imageType' => $this->itemImageType,
                'briefLength' => $this->briefLength,
                'showCategory' => $this->showItemCategory,
                'showCategoryIcon' => $this->showItemCategoryIcon,
                'showCategoryWithIcon' => $this->showItemCategoryWithIcon,
                'showDate' => $this->showItemDate,
                'showViews' => $this->showItemViews,
                'imageContainerOptions'=>$this->itemImageContainerOptions,
                'contentContainerOptions'=>$this->itemContentContainerOptions,
                'articleOptions'=>$this->articleOptions
            ],


            'pager' => $pager
        ]);


    }

    protected function getOrderFromType()
    {
        switch ($this->type) {
            case self::TYPE_RANDOM:
                return new Expression('rand()');
            case self::TYPE_RECENT:
                return ['created_at' => SORT_DESC];
            case self::TYPE_POPULAR:
                return ['click' => SORT_DESC];
            default:
                return [];
        }
    }
}
