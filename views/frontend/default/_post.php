<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use yii\helpers\Html;

?>
<div class="col-xs-12">
    <div class="col-xs-3">
        <?= Html::a(Html::img($model->getThumbFileUrl('banner', 'mthumb'), ['class' => 'img-responsive']), $model->url) ?>
    </div>
    <div class="col-xs-9">
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

            <span><i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDatetime($model->created_at); ?></span>

            <?php if ($model->module->showClicksInArchive): ?>
                <span><i class="fa fa-eye"></i> <?= $model->click; ?></span>
            <?php endif; ?>

        </div>
    </div>
</div>
