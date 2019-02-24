<?php

namespace diazoxide\blog\widgets;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\traits\IActiveStatus;
use kop\y2sp\ScrollPager;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Html;
use yii\widgets\ListView;

class Posts extends \yii\bootstrap\Widget
{
    const TYPE_RANDOM = 'random';
    const TYPE_POPULAR = 'hot';
    const TYPE_RECENT = 'recent';

    public $type = self::TYPE_RECENT;
    public $itemsCount = 10;
    public $itemImageType = 'xthumb';

    public $pjax = false;

    public $categoryId;
    public $showCategoryTitle = false;
    public $showItemCategoryTitle = false;

    public $showItemReadMoreButton = false;
    public $itemReadMoreText = "Read more";
    public $showItemViews = false;

    public $showBrief = true;
    public $briefLength = 150;
    public $daysInterval = 0;
    public $offset = 0;

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        $query = BlogPost::find();
        $query->where(['status' => IActiveStatus::STATUS_ACTIVE])->orderBy($this->getOrderFromType())->limit($this->itemsCount)->offset($this->offset);

        if ($this->daysInterval) {
            $query->andWhere('FROM_UNIXTIME(created_at) > NOW() - INTERVAL ' . $this->daysInterval . ' DAY');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->itemsCount,
                'pageParam' => $this->id . '_page',
                'pageSizeParam' => $this->id . '_page_size',
            ]
        ]);

        if ($this->categoryId) {
            $category = BlogCategory::findOne($this->categoryId);
            if ($category) {
                if ($this->showCategoryTitle) {
                    echo Html::tag('div', $category->icon . ' ' . $category->title, ['class' => 'row widget_title']);
                }
                $dataProvider->query->andWhere(['category_id' => $this->categoryId]);
            }

        }
        $listViewId = "Posts-widget-" . $this->id;

        if($this->pjax) {
            \yii\widgets\Pjax::begin();
        }

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'id' => $listViewId,
                'class' => "post_widget"
            ],
            'layout' => "{items}",
            'itemView' => '@app/modules/blog/widgets/views/_post_item',
            'viewParams' => [
                'showBrief' => $this->showBrief,
                'briefLength' => $this->briefLength,
                'showCategoryTitle' => $this->showItemCategoryTitle,
                'showReadMoreButton'=>$this->showItemReadMoreButton,
                'readMoreText'=>$this->itemReadMoreText,
                'showViews'=>$this->showItemViews,
                'imageType'=>$this->itemImageType
            ],

            'pager' => false,
//            'pager' => [
//                'class' => ScrollPager::className(),
//                'container' => "#$listViewId",
//                'enabledExtensions' => [ScrollPager::EXTENSION_SPINNER, ScrollPager::EXTENSION_NONE_LEFT, ScrollPager::EXTENSION_PAGING],
//                'eventOnScroll' => 'function() {$(\'.ias-trigger a\').trigger(\'click\')}',
//            ]
        ]);

        if($this->pjax) {
            \yii\widgets\Pjax::end();
        }
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
