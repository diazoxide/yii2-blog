<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 11.04
 * Time: 19:37
 */

use \diazoxide\blog\Module;
use \yii\helpers\Html;
?>

<div class="container-fluid">

    <h1><?= Module::t('', 'Import from another platform.') ?></h1>

    <div>
        <?= Html::a(Module::t('', 'WordPress'), ['wordpress']) ?>
    </div>
</div>
