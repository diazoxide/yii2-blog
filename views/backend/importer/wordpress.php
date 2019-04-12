<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 11.04
 * Time: 19:37
 */

use \diazoxide\blog\Module;
use \yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="container-fluid">

    <h1><?= Module::t('', 'WordPress Importer') ?></h1>

    <div>
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <?= $form->errorSummary($model); ?>

        <div class="row top-buffer-20">
            <div class="col-md-8">

                <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
                <?= Html::submitButton(Module::t('', 'Start Importing'), ['class' => 'btn btn-warning']) ?>


            </div>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
