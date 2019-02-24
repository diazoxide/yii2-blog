<?php

namespace diazoxide\yii2blog\widgets;

use diazoxide\yii2blog\models\BlogPost;
use diazoxide\yii2blog\models\BlogPostSearch;
use diazoxide\yii2blog\traits\IActiveStatus;
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
