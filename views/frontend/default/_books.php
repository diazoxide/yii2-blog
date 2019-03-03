<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 02.03
 * Time: 14:56
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/** @var $books */
/** @var \yii\db\ActiveQuery $books */

$dataProvider = new ActiveDataProvider([
    'query' => $books->andWhere(['status'=>\diazoxide\blog\traits\IActiveStatus::STATUS_ACTIVE]),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_book',
]);