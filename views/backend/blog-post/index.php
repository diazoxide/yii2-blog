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
        <?= Html::a(Module::t('', 'Create ') . Module::t('', 'Blog Post'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php \yii\widgets\Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'banner',
                'value' => function ($model) {
                    return Html::img($model->getThumbFileUrl('banner', 'xsthumb'));
                },
                'format' => 'raw',
                'filter' => false
            ],
            [
                'format' => 'raw',
                'attribute' => 'title',
                'value' => function ($model) {
                    return Html::a(\yii\helpers\StringHelper::truncate(Html::encode($model->title), 100, "..."), $model->url);
                }
            ],
            [
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
            ],
            'click',
            'commentsCount',
            [
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
            ],
            [
                'attribute' => 'user_id',
                'value' => 'user.username',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'user_id',
                    \yii\helpers\ArrayHelper::map(\dektrium\user\models\User::find()->all(), 'id', 'username'),
                    ['class' => 'form-control', 'prompt' => Module::t('', 'Author')]
                )
            ],
            'published_at:relativeTime',
            'created_at:dateTime',
            'updated_at:dateTime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
