<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 02.03
 * Time: 14:56
 */

use yii\helpers\Html;

/** @var \diazoxide\blog\models\BlogPostBook $model */

?>


<div class="col-sm-4">

    <div class="col-xs-12">
        <?= Html::a(Html::img($model->getThumbFileUrl('banner', 'mthumb')), $model->url) ?>
    </div>

    <?= Html::a($model->title, $model->url) ?>

</div>
