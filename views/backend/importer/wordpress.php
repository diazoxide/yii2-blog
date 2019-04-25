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

/** @var Wordpress $wordpress */
?>

<div class="container-fluid">

    <h1><?= Module::t('', 'WordPress Importer') ?></h1>


    <div>
        <?php /** @var string $action */
        if ($action == ImporterController::ACTION_VALIDATE):?>
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <?=
            $form->errorSummary($wordpress); ?>
            <div class="row top-buffer-20">
                <div class="col-md-8">
                    <?= $form->field($wordpress, 'url')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($wordpress, 'per_page')->textInput(['type' => 'number']) ?>
                    <?= $form->field($wordpress, 'page')->textInput(['type' => 'number']) ?>
                    <?= $form->field($wordpress, 'import_categories')->checkbox() ?>
                    <?= $form->field($wordpress, 'overwrite')->checkbox() ?>
                    <?= $form->field($wordpress, 'localize_content')->checkbox() ?>
                    <?= $form->field($wordpress, 'post_type_id')->dropDownList(ArrayHelper::map(BlogPostType::find()->all(), 'id', 'name'), ['prompt' => Module::t('', 'Select Post Type')]) ?>
                    <?= Html::submitButton(Module::t('', 'Start Importing'), ['class' => 'btn btn-warning']) ?>
                </div>
            </div>
            <?= Html::hiddenInput('action', ImporterController::ACTION_IMPORT_CATEGORIES) ?>
            <?php ActiveForm::end(); ?>
        <?php elseif ($action == ImporterController::ACTION_SUCCESS): ?>

            <h1><?= Module::t('', 'Importing done.') ?></h1>

        <?php elseif ($action == ImporterController::ACTION_IMPORT_POSTS): ?>

            <p>Total: <?= $wordpress->total ?></p>
            <p>Pages: <?= $wordpress->total_pages ?></p>
            <p>Per page: <?= $wordpress->per_page ?></p>
            <p>Current page: <?= $wordpress->page ?></p>

            <?php
            $percent = round($wordpress->page * 100 / $wordpress->total_pages);
            $this->title = $percent.'% '.Module::t('','Post import in progress');
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