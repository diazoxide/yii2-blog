<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

use app\modules\blog\models\BlogPost;
use app\modules\blog\models\Status;
use app\modules\blog\Module;
use app\modules\blog\traits\IActiveStatus;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\blog\models\BlogPostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('blog', 'Blog Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Module::t('blog', 'Create ') . Module::t('blog', 'Blog Post'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php \yii\widgets\Pjax::begin();?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'banner',
                'value' => function ($model) {
                    return Html::img($model->getThumbFileUrl('banner', 'thumb'), ['class' => 'img-responsive', 'width' => 100]);
                },
                'format' => 'raw',
                'filter' => false
            ],
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return \yii\helpers\StringHelper::truncate(Html::encode($model->title), 50, "...");
                }
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return \yii\helpers\StringHelper::truncate(Html::encode($model->category->title), 50, "...");
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'category_id',
                    BlogPost::getArrayCategory(),
                    ['class' => 'form-control', 'prompt' => Module::t('blog', 'Please Filter')]
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
                    ['class' => 'form-control', 'prompt' => Module::t('blog', 'PROMPT_STATUS')]
                )
            ],
            [
                'attribute' => 'user_id',
                'value'=>'user.username',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'user_id',
                    \yii\helpers\ArrayHelper::map(\dektrium\user\models\User::find()->all(),'id','username'),
                    ['class' => 'form-control', 'prompt' => Module::t('blog', 'Author')]
                )
            ],
            'created_at:date',
            'updated_at:date',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end();?>

</div>
