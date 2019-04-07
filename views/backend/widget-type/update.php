<?php

use diazoxide\blog\Module;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogWidgetType */

$this->title = Module::t('', 'Update ') . Module::t('', 'Widget Type') . ' ' . $model->title;
$this->params['breadcrumbs'] = $model->breadcrumbs;
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="blog-widget-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
