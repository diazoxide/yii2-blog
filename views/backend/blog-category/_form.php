<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogCategory */
/* @var $form yii\widgets\ActiveForm */

//fix the issue that it can assign itself as parent
//$parentCategory = ArrayHelper::merge([0 => Module::t('Root Category')], ArrayHelper::map(BlogCategory::get(0, BlogCategory::find()->all()), 'id', 'str_label'));
//unset($parentCategory[$model->id]);

?>

<div class="blog-category-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-10\">{input}{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(BlogCategory::find()->all(),'id','title')) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'banner')->fileInput() ?>

    <?= $form->field($model, 'icon_class')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'read_icon_class')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'read_more_text')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'is_nav')->dropDownList(BlogCategory::getArrayIsNav()) ?>

    <?= $form->field($model, 'is_featured')->dropDownList(BlogCategory::getArrayIsFeatured()) ?>

    <?= $form->field($model, 'widget_type_id')->dropDownList(ArrayHelper::map(\diazoxide\blog\models\BlogWidgetType::find()->all(), 'id', 'title'),['prompt'=>Module::t('', 'Select value')]) ?>

    <?= $form->field($model, 'page_size')->textInput() ?>

    <?= $form->field($model, 'template')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'redirect_url')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'status')->dropDownList(BlogCategory::getStatusList()) ?>

    <div class="form-group">
        <label class="col-lg-2 control-label" for="">&nbsp;</label>
        <?= Html::submitButton($model->isNewRecord ? Module::t('', 'Create') : Module::t('', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
