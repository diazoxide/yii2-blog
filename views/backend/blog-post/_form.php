<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\Module;
//use kartik\markdown\MarkdownEditor;
use \kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
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


            <?php echo $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::class, [
                'moduleId' => $model->module->redactorModule,
                'clientOptions' => [
                    'plugins' => ['clips',
                        'advanced',
                        'fullscreen',
                        'counter',
                        'fontcolor',
                        'fontfamily',
                        'fontsize',
                        'handle',
                        'inlinestyle',
                        'properties',
                        'specialchars',
                        'table',
                        'textdirection',
                        'textexpander',
                        'variable',
                        'video', 'imagemanager']
                ]
            ]); ?>
            <?php /*echo $form->field($model, 'content')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advanced',
                'kcfinder' => true,
                'kcfOptions' => [
                    'uploadURL' => $model->module->imgFileUrl,
                    'uploadDir' => $model->module->imgFilePath,
                    'access' => [  // @link http://kcfinder.sunhater.com/install#_access
                        'files' => [
                            'upload' => true,
                            'delete' => true,
                            'copy' => true,
                            'move' => true,
                            'rename' => true,
                        ],
                        'dirs' => [
                            'create' => true,
                            'delete' => true,
                            'rename' => true,
                        ],
                    ],
                    'types' => [  // @link http://kcfinder.sunhater.com/install#_types
                        'files' => [
                            'type' => '',
                        ],
                    ],
                ],
            ])*/ ?>
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
                    'placeholder' => Module::t('Select Main Category'),
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

            <?=
            $form->field($model, 'created')->widget(DateTimePicker::className(), [
                'name' => 'created',
                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                'options' => [
                    'placeholder' => Module::t('Select Publish Datetime'),
                    'value' => !$model->isNewRecord ? Yii::$app->formatter->asDatetime($model->created_at) : ""
                ],
                'convertFormat' => true,
                'pluginOptions' => [
                    'format' => Yii::$app->formatter->datetimeFormat,
                    'autoclose' => true,
                    'weekStart' => 1, //неделя начинается с понедельника
                    'startDate' => '01.05.2015 00:00', //самая ранняя возможная дата
                    'todayBtn' => true, //снизу кнопка "сегодня"
                ]
            ]); ?>

            <?= $form->field($model, 'status')->dropDownList(\diazoxide\blog\models\BlogPost::getStatusList()) ?>

        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
