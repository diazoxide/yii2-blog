<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogCategory */

$this->title = Module::t('blog', 'Update ') . Module::t('blog', 'Blog Category') . ' ' . $model->title;
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="blog-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
