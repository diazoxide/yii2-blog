<?php

namespace app\modules\blog\widgets;

use app\modules\blog\models\BlogCategory;
use app\modules\blog\models\BlogPostSearch;
use kop\y2sp\ScrollPager;
use Yii;
use yii\helpers\Html;
use yii\widgets\ListView;

class Posts extends \yii\bootstrap\Widget
{
    public $itemsCount = 10;
    public $categoryId;
    public $showCategoryTitle = false;
    public $showBrief = true;
    public $briefLength = 100;

    public function init()
    {
        parent::init();

        $searchModel = new BlogPostSearch();
        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = $this->itemsCount;

        if ($this->categoryId) {
            $category = BlogCategory::findOne($this->categoryId);
            if($category) {
                if($this->showCategoryTitle) {
                    echo Html::tag('h3', $category->title);
                }
                $dataProvider->query->andWhere(['category_id' => $this->categoryId]);
            }

        }
        $listViewId = "Posts-widget-" . $this->id;

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'id' => $listViewId,
                'class'=>"post_widget"
            ],
            'layout' => "{items}",
            'itemView' => '@app/modules/blog/widgets/views/_post_item',
            'viewParams' => ['showBrief' => $this->showBrief,'briefLength'=>$this->briefLength],

            'pager' => false,
//            'pager' => [
//                'class' => ScrollPager::className(),
//                'container' => "#$listViewId",
//                'enabledExtensions' => [ScrollPager::EXTENSION_SPINNER, ScrollPager::EXTENSION_NONE_LEFT, ScrollPager::EXTENSION_PAGING],
//                'eventOnScroll' => 'function() {$(\'.ias-trigger a\').trigger(\'click\')}',
//            ]
        ]);
    }

}
