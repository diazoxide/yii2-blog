<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogCategory */

$this->title = Module::t('Create ') . Module::t('Blog Category');
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
