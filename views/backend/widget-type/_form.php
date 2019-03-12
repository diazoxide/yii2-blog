<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogWidgetType */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="blog-widget-type-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-10\">{input}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?php print_r($model->getConfigData()); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>


    <?= $form->field($model, 'config_data[show_category_title]')->checkbox() ?>
    <?= $form->field($model, 'config_data[show_brief]')->checkbox() ?>
    <?= $form->field($model, 'config_data[brief_length]')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'config_data[infinite_scroll]')->checkbox() ?>
    <?= $form->field($model, 'config_data[show_pager]')->checkbox() ?>
    <?= $form->field($model, 'config_data[load_more_button]')->checkbox() ?>

    <?= $form->field($model, 'config_data[show_item_category]')->checkbox() ?>
    <?= $form->field($model, 'config_data[show_item_category_icon]')->checkbox() ?>
    <?= $form->field($model, 'config_data[show_item_category_with_icon]')->checkbox() ?>
    <?= $form->field($model, 'config_data[show_item_views]')->checkbox() ?>
    <?= $form->field($model, 'config_data[show_item_date]')->checkbox() ?>
    <?= $form->field($model, 'config_data[days_interval]')->textInput(['type' => 'number']) ?>



    <?= $form->field($model, 'config_data[items_count]')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'config_data[offset]')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'config_data[item_image_type]')->textInput(['maxlength' => 60]) ?>
    <?= $form->field($model, 'config_data[item_image_container_options]')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'config_data[item_content_container_options]')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'config_data[article_options]')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'config_data[list_options]')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'config_data[list_options]')->textInput(['maxlength' => 255]) ?>


    <div class="form-group">
        <label class="col-lg-2 control-label" for="">&nbsp;</label>
        <?= Html::submitButton($model->isNewRecord ? Module::t('Create') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
