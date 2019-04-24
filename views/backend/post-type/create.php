<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 23.04
 * Time: 15:14
 */

use \diazoxide\blog\Module;
$this->title = Module::t('', 'Create ') . Module::t('', 'Post Type');
$this->params['breadcrumbs'][] = ['label' => Module::t('', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <h1><?= $this->title ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>


</div>
