<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;


/* @var $this yii\web\View */
/* @var $model backend\modules\blog\models\BlogPost */

$this->title = Module::t('', 'Create ') . Module::t('', 'Blog Post');
$this->params['breadcrumbs'][] = ['label' => Module::t('', 'Blog Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-post-create">

    <?= /** @var \diazoxide\blog\models\BlogPostType $type */
    $this->render('_form', [
        'model' => $model,
        'type'=>$type
    ]) ?>

</div>
