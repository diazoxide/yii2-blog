<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */

$this->title = Module::t('blog', 'Update ') . Module::t('blog', 'Blog Post Book') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('blog', 'Blog Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->post->title, 'url' => ['update', 'id' => $model->post_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update-book', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('blog', 'Update');
?>
<div class="blog-post-book-update">

    <?= $this->render('_book_form', [
        'model' => $model,
    ]) ?>

</div>
