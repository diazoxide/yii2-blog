<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */

$this->title = Module::t('blog', 'Create ') . Module::t('blog', 'Chapter');
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-book-chapter-create">

    <?= $this->render('_book_chapter_form', [
        'model' => $model,
    ]) ?>

</div>
