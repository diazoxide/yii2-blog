<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */

$this->title = Module::t('Create ');
$this->params['breadcrumbs'][] = ['label' => Module::t('Blog Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->post->title, 'url' => ['update', 'id' => $model->post_id]];
$this->params['breadcrumbs'][] = ['label' => Module::t('Books')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-book-create">

    <?= $this->render('_book_form', [
        'model' => $model,
    ]) ?>

</div>
