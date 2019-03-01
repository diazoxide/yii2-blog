<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Module::t('blog', 'Update ') . Module::t('blog', 'Chapter') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->book->post->title, 'url' => ['update', 'id' => $model->book->post_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Books')];
$this->params['breadcrumbs'][] = ['label' => $model->book->title, 'url' => ['update-book', 'id' => $model->book_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>
<div class="blog-post-book-chapter-update">

    <?= $this->render('_book_chapter_form', [
        'model' => $model,
    ]) ?>

    <div class="col-sm-6">
        <div class="col-xs-12 text-right">
            <?= Html::a('Create Chapter', ['create-book-chapter', 'book_id' => $model->book_id, 'parent_id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>
        <?php
        $chaptersDataProvider = new ActiveDataProvider([
            'query' => $model->getChapters(),
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
