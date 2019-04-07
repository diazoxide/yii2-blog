<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */

/** @var \diazoxide\blog\models\BlogPost $model */
$this->title = $model->title;
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-view">
    
    <p>
        <?= Html::a(Module::t('', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'category_id',
                'value' => $model->category->title,
            ],
            'title',
            'brief:ntext',
            'content:html',
            'tags',
            'slug',
            [
                'attribute' => 'banner',
                'value' => $model->getThumbFileUrl('banner', 'thumb'),
            ],
            'click',
            [
                'attribute' => 'user',
                'value' => ($model->user) ? $model->user->{Module::getInstance()->userName} : 'Отсутствует',
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
