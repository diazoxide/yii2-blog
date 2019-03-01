<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */

$this->title = Module::t('blog', 'Create ') . Module::t('blog', 'Chapter');
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->book->post->title, 'url' => ['update', 'id' => $model->book->post_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Books')];
$this->params['breadcrumbs'][] = ['label' => $model->book->title, 'url' => ['update-book', 'id' => $model->book->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="blog-post-book-chapter-create">

    <?= $this->render('_book_chapter_form', [
        'model' => $model,
    ]) ?>

</div>
