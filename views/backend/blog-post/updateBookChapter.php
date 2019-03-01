<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */

$this->title = Module::t('blog', 'Update ') . Module::t('blog', 'Chapter') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Blog Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->book->post->title, 'url' => ['update', 'id' => $model->book->post_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Blog Posts Books')];
$this->params['breadcrumbs'][] = ['label' => $model->book->title, 'url' => ['update-book', 'id' => $model->book_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>
<div class="blog-post-book-chapter-update">

    <?= $this->render('_book_chapter_form', [
        'model' => $model,
    ]) ?>

</div>
