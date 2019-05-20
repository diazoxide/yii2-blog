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

/** @var Wordpress $csv */
?>

<div class="container-fluid">

    <h1><?= Module::t('', 'CSV Importer') ?></h1>

    <div>
        <?php /** @var string $action */
        if ($action == ImporterController::ACTION_VALIDATE):?>
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <?=
            $form->errorSummary($csv); ?>
            <div class="row top-buffer-20">
                <div class="col-md-8">
                    <?= $form->field($csv, 'unique_key')->textInput(['maxlength' => 16])->hint(Module::t('', 'Required field, for next overwrite.')) ?>
                    <?= $form->field($csv, 'url')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($csv, 'enclosure')->textInput(['maxLength'=>16]) ?>
                    <?= $form->field($csv, 'delimiter')->textInput(['maxLength'=>16]) ?>
                    <?= $form->field($csv, 'fields')->textarea(['rows' => 3])->hint(Module::t('', 'Comma separated fields name in order.')) ?>
                    <?= $form->field($csv, 'per_page')->textInput(['type' => 'number']) ?>
                    <?= $form->field($csv, 'page')->textInput(['type' => 'number']) ?>
                    <?= $form->field($csv, 'import_categories')->checkbox() ?>
                    <?= $form->field($csv, 'overwrite')->checkbox() ?>
                    <?= $form->field($csv, 'localize_content')->checkbox() ?>
                    <?= $form->field($csv, 'post_type_id')->dropDownList(ArrayHelper::map(BlogPostType::find()->all(), 'id', 'name'), ['prompt' => Module::t('', 'Select Post Type')]) ?>
                    <?= Html::submitButton(Module::t('', 'Start Importing'), ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
            <?= Html::hiddenInput('action', ImporterController::ACTION_IMPORT_POSTS) ?>
            <?php ActiveForm::end(); ?>
        <?php elseif ($action == ImporterController::ACTION_SUCCESS): ?>

            <h1><?= Module::t('', 'Importing done.') ?></h1>

        <?php elseif ($action == ImporterController::ACTION_IMPORT_POSTS): ?>

            <p>Total: <?= $csv->total ?></p>
            <p>Pages: <?= $csv->total_pages ?></p>
            <p>Per page: <?= $csv->per_page ?></p>
            <p>Current page: <?= $csv->page ?></p>

            <?php
            $percent = round($csv->page * 100 / $csv->total_pages);
            $this->title = $percent . '% ' . Module::t('', 'Post import in progress');
            echo yii\bootstrap\Progress::widget([
                'percent' => $percent,
                'barOptions' => ['class' => 'progress-bar-danger'],
                'options' => ['class' => 'active progress-striped'],
                'label' => $percent . '%',

            ]);
            ?>
        <?php endif; ?>
    </div>
</div>