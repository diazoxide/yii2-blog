<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 02.03
 * Time: 19:41
 */

use diazoxide\blog\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

/** @var \diazoxide\blog\models\BlogPostBook $book */

$this->title = $book->title;
$this->params['breadcrumbs'] = $book->breadcrumbs;
$this->params['breadcrumbs'][] =  $book->title;

$dataProvider = new ActiveDataProvider([
    'query' => $book->getChapters()->andWhere(['parent_id'=>null]),
    'pagination' => [
        'pageSize' => 50,
    ],
]);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_chapter',
    'itemOptions'=>[
        'class'=>'col-sm-3 top-buffer-20'
    ],
]);