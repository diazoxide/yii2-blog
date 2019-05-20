<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 23.04
 * Time: 15:15
 */
?>

<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 11.04
 * Time: 19:37
 */

use diazoxide\blog\controllers\backend\ImporterController;
use diazoxide\blog\models\importer\Wordpress;
use diazoxide\blog\Module;
use diazoxide\blog\models\BlogPostType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var Wordpress $model */
?>

<div class="container-fluid">

    <div>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
        <?=
        $form->errorSummary($model); ?>
        <div class="row top-buffer-20">
            <div class="col-md-8">
                <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
                <?= $form->field($model, 'layout')->textInput(['maxlength' => 255]) ?>
                <?= $form->field($model, 'single_pattern')->textarea(['rows' => 5]) ?>
                <?= $form->field($model, 'archive_pattern')->textarea(['rows' => 5]) ?>
                <?= $form->field($model, 'default_pattern')->textarea(['rows' => 5]) ?>
                <?= $form->field($model, 'url_pattern')->textInput(['maxlength' => 255]) ?>
                <?= $form->field($model, 'has_title')->checkbox() ?>
                <?= $form->field($model, 'has_content')->checkbox() ?>
                <?= $form->field($model, 'has_category')->checkbox() ?>
                <?= $form->field($model, 'has_brief')->checkbox() ?>
                <?= $form->field($model, 'has_comment')->checkbox() ?>
                <?= $form->field($model, 'has_banner')->checkbox() ?>
                <?= $form->field($model, 'has_book')->checkbox() ?>
                <?= $form->field($model, 'has_tag')->checkbox() ?>
                <?= Html::submitButton(Module::t('', 'Save'), ['class' => 'btn btn-warning']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
