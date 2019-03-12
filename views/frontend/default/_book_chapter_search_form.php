<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="blog-comment-form">
    <?php $form = ActiveForm::begin([
        'id' => 'boook-chapter-sarch-form',
        'method'=>"get"
    ]); ?>

    <?= $form->field($model, 'q')->textInput((['maxlength' => 255])); ?>


    <?= Html::submitButton(Module::t('Search'), ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>
</div>
