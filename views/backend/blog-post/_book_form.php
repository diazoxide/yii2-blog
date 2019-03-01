<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\Module;
//use kartik\markdown\MarkdownEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogPost */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-post-book-form">


    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <?= Html::submitButton($model->isNewRecord ? Module::t('blog', 'Create') : Module::t('blog', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-warning']) ?>
        </div>
    </div>


    <div class="row top-buffer-20">
        <div class="col-md-8">

            <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => 128, 'class' => 'form-control input-sm', 'readonly' => true, 'onclick' => "this.removeAttribute('readonly')"]) ?>


            <?= $form->field($model, 'brief')->textarea(['rows' => 4]) ?>

        </div>

        <div class="col-md-4">

            <?= $form->field($model, 'banner')->fileInput() ?>

            <?= $form->field($model, 'status')->dropDownList(\diazoxide\blog\models\BlogPost::getStatusList()) ?>

        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
