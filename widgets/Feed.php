<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\FeedTrait;
use kop\y2sp\ScrollPager;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Html;
use yii\widgets\ListView;

class Feed extends \yii\bootstrap\Widget
{
    use FeedTrait;
    const TYPE_RANDOM = 1;
    const TYPE_POPULAR = 2;
    const TYPE_RECENT = 3;


    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $listViewId = "Feed-widget-" . $this->id;

        $query = BlogPost::find();

        $query->where(['status' => IActiveStatus::STATUS_ACTIVE])
            ->orderBy($this->getOrderFromType());


        if ($this->days_interval) {
            $query->andWhere('FROM_UNIXTIME(created_at) > NOW() - INTERVAL ' . $this->days_interval . ' DAY');
        }

        if ($this->category_id) {
            $category = BlogCategory::findOne($this->category_id);
            if ($category) {
                if ($this->show_category_title) {
                    echo Html::tag('div', $category->icon . ' ' . $category->title, ['class' => 'widget_title']);
                }
                $query->andWhere(['category_id' => $this->category_id]);
            }

        }

        $pager = null;

        if ($this->infinite_scroll || $this->load_more_button) {
            $this->show_pager = true;
            $pager = [
                'class' => ScrollPager::className(),
                'container' => "#$listViewId",
                'triggerText' => Module::t('Load more...')
            ];
        }

        if (!$this->show_pager) {
            $query->offset($this->offset);
            $query->limit($this->items_count);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $this->show_pager ? [
                'pageSize' => $this->items_count,
                'pageParam' => $this->id . '_page',
                'pageSizeParam' => $this->id . '_page_size',
            ] : false
        ]);


        if ($this->infinite_scroll) {
            $pager['enabledExtensions'] = [ScrollPager::EXTENSION_SPINNER, ScrollPager::EXTENSION_NONE_LEFT, ScrollPager::EXTENSION_PAGING];
            $pager['overflowContainer'] = "#$listViewId";
        }
        $this->list_options['id'] = $listViewId;
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'id' => $listViewId,
                'class' => 'feed-widget-listview'
            ],
            'itemOptions' => $this->article_options,
            'layout' => "{items}" . ($this->show_pager ? "{pager}" : ""),
            'itemView' => '@vendor/diazoxide/yii2-blog/widgets/views/_feed_item',
            'viewParams' => [
                'showBrief' => $this->show_brief,
                'imageType' => $this->item_image_type,
                'briefLength' => $this->brief_length,
                'showCategory' => $this->show_item_category,
                'showCategoryIcon' => $this->show_item_category_icon,
                'showCategoryWithIcon' => $this->show_item_category_with_icon,
                'showDate' => $this->show_item_date,
                'showViews' => $this->show_item_views,
                'imageContainerOptions' => $this->item_image_container_options,
                'contentContainerOptions' => $this->item_content_container_options,
                'articleOptions' => $this->article_options
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
