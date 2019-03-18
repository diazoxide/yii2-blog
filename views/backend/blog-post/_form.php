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

<div class="blog-post-form">


    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <?= Html::submitButton($model->isNewRecord ? Module::t('Create') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-warning']) ?>
        </div>
    </div>


    <div class="row top-buffer-20">
        <div class="col-md-8">

            <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => 128, 'class' => 'form-control input-sm', 'readonly' => true, 'onclick' => "this.removeAttribute('readonly')"]) ?>


            <?= $form->field($model, 'brief')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::class, [
                'moduleId' => $model->module->redactorModule,
                'clientOptions' => [
                    'plugins' => ['clips', 'fontcolor', 'imagemanager']
                ]
            ]); ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'category_ids')->widget(\kartik\select2\Select2::classname(), [
                'data' => ArrayHelper::map(BlogCategory::find()->all(), 'id', 'title'),
                //'language' => 'de',
                'options' => [
                    'placeholder' => Module::t('Select Categories'),
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>


            <?=
            $form->field($model, 'category_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => ArrayHelper::map(BlogCategory::find()->all(), 'id', 'title'),
                //'language' => 'de',
                'options' => [
                    'placeholder' => Module::t('Select Categories'),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>

            <?= $form->field($model, 'is_slide')->dropDownList([0 => Module::t('No'), 1 => Module::t('Yes')], ['prompt' => Module::t('Select value')]) ?>

            <?= $form->field($model, 'show_comments')->dropDownList([0 => Module::t('No'), 1 => Module::t('Yes')], ['prompt' => Module::t('Select value')]) ?>

            <?= $form->field($model, 'tags')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'banner')->fileInput() ?>

            <?= $form->field($model, 'click')->textInput() ?>

            <?= $form->field($model, 'created_at')->textInput() ?>
            <?= $form->field($model, 'updated_at')->textInput() ?>

            <?= $form->field($model, 'status')->dropDownList(\diazoxide\blog\models\BlogPost::getStatusList()) ?>

        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
