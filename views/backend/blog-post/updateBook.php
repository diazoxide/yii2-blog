<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/** @var \diazoxide\blog\models\BlogPostBook $model */
$this->title = Module::t('', 'Update ') . Module::t('', 'Blog Post Book') . ' ' . $model->title;
$this->params['breadcrumbs'] = $model->breadcrumbs;
?>
<div class="blog-post-book-update">

    <?=
    $this->render('_book_form', [
        'model' => $model,
    ]) ?>

    <div class="col-sm-6">

        <h4><?= Module::t('', 'Chapters') ?></h4>

        <div class="text-right">
            <?= Html::a('Create Chapter', ['create-book-chapter', 'book_id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>

        <?php
        $chaptersDataProvider = new ActiveDataProvider([
            'query' => $model->getChapters()->andWhere(['parent_id' => null]),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'chapter_page',
                'pageSizeParam' => 'chapter_page_size',
            ],
            'sort' => [
                'sortParam' => 'chapter_sort'
            ]
        ]);

        /** @var \yii\debug\models\timeline\DataProvider $bookDataProvider */
        echo GridView::widget([
            'dataProvider' => $chaptersDataProvider,
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
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Actions',
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'template' => '{update}{delete}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'update') {
                            return Url::toRoute(['update-book-chapter', 'id' => $model->id]);

                        }
                        if ($action === 'delete') {
                            return Url::toRoute(['delete-book-chapter', 'id' => $model->id]);

                        }
                    }
                ],

            ],
        ]);
        ?>
    </div>

</div>
