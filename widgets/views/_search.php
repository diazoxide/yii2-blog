<?php

use diazoxide\blog\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'id' => 'search-form',
    'method' => 'get',
    'action' => '/archive',
    'fieldConfig' => [
        'options' => [
            'tag' => false,
        ],
    ],
    'options' => ['class' => 'navbar-form navbar-right']
]); ?>

<div class="input-group">
    <?= $form
        ->field($model, 'q',
            ['errorOptions' => ['tag' => null]]
        )
        ->textInput((['maxlength' => 255, 'placeholder' => Module::t("blog", 'Search')]))
        ->label(false); ?>


    <div class="input-group-btn">
        <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
