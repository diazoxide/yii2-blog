<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var \diazoxide\blog\models\BlogPost $model */
/* @var $this yii\web\View */

$this->title = Module::t('', 'Update ') . Module::t('', 'Blog Post') . ' ' . $model->title;
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>
<div class="blog-post-update">

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $model->type
    ]) ?>

    <?php if ($model->type->has_book) : ?>

        <div class="col-sm-6">
            <h4><?= Module::t('', 'Books') ?></h4>
            <div class="text-right">
                <?= Html::a('Create Book', ['create-book', 'post_id' => $model->id], ['class' => 'btn btn-default']) ?>
            </div>
            <?=
            /** @var \yii\debug\models\timeline\DataProvider $bookDataProvider */
            GridView::widget([
                'dataProvider' => $bookDataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => "banner",
                        'format' => 'raw',
                        'value' => function ($model) {
                            /** @var \diazoxide\blog\models\BlogPostBook $model */
                            return Html::img($model->getThumbFileUrl('banner', "xsthumb"));
                        }
                    ],
                    'title',
                    'brief',
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' =>
                            function ($model) {
                                /** @var \diazoxide\blog\models\BlogPost $model */
                                if ($model->status === IActiveStatus::STATUS_ACTIVE) {
                                    $class = 'label-success';
                                } elseif ($model->status === IActiveStatus::STATUS_INACTIVE) {
                                    $class = 'label-warning';
                                } else {
                                    $class = 'label-danger';
                                }

                                return '<span class="label ' . $class . '">' . $model->getStatus() . '</span>';
                            },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'headerOptions' => ['style' => 'color:#337ab7'],
                        'template' => '{update} {delete}',
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'update') {
                                return Url::toRoute(['update-book', 'id' => $model->id]);

                            }
                            if ($action === 'delete') {
                                return Url::toRoute(['delete-book', 'id' => $model->id]);

                            }
                            return true;
                        }
                    ],

                ],
            ]);
            ?>
        </div>
    <?php endif; ?>


</div>


