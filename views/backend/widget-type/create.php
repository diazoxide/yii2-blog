<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogWidgetType */

$this->title = Module::t('Create ') . Module::t('Widget Type');
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-widget-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
