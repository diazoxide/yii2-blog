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

class Search extends \yii\bootstrap\Widget
{

    public function init()
    {
        parent::init();
        $model = new BlogPostSearch();
        $model->scenario = $model::SCENARIO_USER;
        echo $this->render('_search',[
            'model'=>$model
        ]);

    }

}
