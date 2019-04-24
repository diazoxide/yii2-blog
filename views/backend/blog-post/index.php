<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\models\BlogPost;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel diazoxide\blog\models\BlogPostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('', 'Blog Posts');
/** @var array $breadcrumbs */
$this->params['breadcrumbs'] = $breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= /** @var \diazoxide\blog\models\BlogPostType $type */
        Html::a(Module::t('', 'Create ') . Module::t('', 'Blog Post'), ['create', 'type' => $type->name], ['class' => 'btn btn-success']) ?>
    </p>
    <?php \yii\widgets\Pjax::begin(); ?>

    <?php

    $columns = [
        ['class' => 'yii\grid\CheckboxColumn'],
    ];
    if ($type->has_banner) {
        $columns[] = [
            'attribute' => 'banner',
            'value' => function ($model) {
                return Html::img($model->getThumbFileUrl('banner', 'xsthumb'));
            },
            'format' => 'raw',
            'filter' => false
        ];
    }
    $columns[] = [
        'format' => 'raw',
        'attribute' => 'title',
        'value' => function ($model) {
            return Html::a(\yii\helpers\StringHelper::truncate(Html::encode($model->title), 100, "..."), $model->url);
        }
    ];

    if ($type->has_category) {
        $columns[] = [
            'format' => 'raw',
            'attribute' => 'category_id',
            'value' => function ($model) use ($searchModel) {
                return Html::a(\yii\helpers\StringHelper::truncate(Html::encode($model->category->title), 50, "..."),
                    ['', $searchModel->formName() => ['category_id' => $model->category->id]]
                );
            },
            'filter' => Html::activeDropDownList(
                $searchModel,
                'category_id',
                BlogPost::getArrayCategory(),
                ['class' => 'form-control', 'prompt' => Module::t('', 'Please Filter')]
            )
        ];
    }
    $columns[] = 'click';

    if ($type->has_comment) {
        $columns[] = 'commentsCount';
    }
    $columns[] = [
        'attribute' => 'status',
        'format' => 'html',
        'value' => function ($model) {
            if ($model->status === IActiveStatus::STATUS_ACTIVE) {
                $class = 'label-success';
            } elseif ($model->status === IActiveStatus::STATUS_INACTIVE) {
                $class = 'label-warning';
            } else {
                $class = 'label-danger';
            }

            return '<span class="label ' . $class . '">' . $model->getStatus() . '</span>';
        },
        'filter' => Html::activeDropDownList(
            $searchModel,
            'status',
            BlogPost::getStatusList(),
            ['class' => 'form-control', 'prompt' => Module::t('', 'PROMPT_STATUS')]
        )
    ];

    $columns[] = 'published_at:relativeTime';
    $columns[] = 'created_at:dateTime';
    $columns[] = 'updated_at:dateTime';
    $columns[] = ['class' => 'yii\grid\ActionColumn'];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
