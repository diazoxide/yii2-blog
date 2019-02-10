<?php

namespace app\modules\blog\widgets;

use app\modules\blog\models\BlogPost;
use app\modules\blog\models\BlogPostSearch;
use app\modules\blog\traits\IActiveStatus;
use kop\y2sp\ScrollPager;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\widgets\ListView;

class Feed extends \yii\bootstrap\Widget
{
    const TYPE_RANDOM = 'random';
    const TYPE_POPULAR = 'hot';
    const TYPE_RECENT = 'recent';

    public $type = self::TYPE_RECENT;
    public $itemsCount = 10;
    public $showBrief = false;
    public $briefLength = 100;
    public $infiniteScroll = false;
    public $showPager = false;
    public $showItemCategory = true;
    public $showItemViews = false;
    public $showItemDate = true;

    public function init()
    {
        parent::init();

        $query = BlogPost::find();
        $query->where(['status' => IActiveStatus::STATUS_ACTIVE])->orderBy($this->getOrderFromType())->limit($this->itemsCount);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->itemsCount,
            ]
        ]);

        $listViewId = "Feed-widget-" . $this->id;

        if ($this->infiniteScroll) {
            $this->showPager = true;
        }

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'id' => $listViewId,
                'class' => 'feed-widget-listview'
            ],
            'layout' => "{items}" . ($this->showPager ? "{pager}" : ""),
            'itemView' => '@app/modules/blog/widgets/views/_feed_item',
            'viewParams' => [
                'showBrief' => $this->showBrief,
                'briefLength' => $this->briefLength,
                'showCategory'=>$this->showItemCategory,
                'showDate'=>$this->showItemDate,
                'showViews'=>$this->showItemViews,
                ],

            'pager' => $this->infiniteScroll ? [
                'class' => ScrollPager::className(),
                'container' => "#$listViewId",
                'enabledExtensions' => [ScrollPager::EXTENSION_SPINNER, ScrollPager::EXTENSION_NONE_LEFT, ScrollPager::EXTENSION_PAGING],
                'eventOnScroll' => 'function() {$(\'.ias-trigger a\').trigger(\'click\')}',
            ] : null
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
