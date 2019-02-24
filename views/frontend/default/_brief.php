<?php
/**
* Project: yii2-blog for internal using
* Author: akiraz2
* Copyright (c) 2018.
*/

use yii\helpers\Html;

?>

<div class="col-xs-3 col-md-12">
    <?= Html::img($model->getThumbFileUrl('banner', 'mthumb'), ['class' => 'img-responsive']) ?>
</div>
<div class="col-xs-9 col-md-12">
    <h4>
        <?= Html::a(Html::encode(yii\helpers\StringHelper::truncate(Html::encode($model->title), 50, '...')), $model->url); ?>
    </h4>

    <h5>
        <?= $model->category->title; ?>
    </h5>

    <div>
        <?php
        echo yii\helpers\StringHelper::truncate(Html::encode($model->brief), 100, '...');
        ?>
    </div>

    <div>
        <span>
            <i class="fa fa-calendar"></i><?= Yii::$app->formatter->asDate($model->created_at); ?>
        </span>
        <span>
            <i class="fa fa-eye"></i><?= $model->click; ?>
        </span>
    </div>
</div>