<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 02.03
 * Time: 14:56
 */

use yii\helpers\Html;

/** @var \diazoxide\blog\models\BlogPostBookChapter $model */

?>


<div class="col-sm-3">

    <div class="col-xs-4">
        <?= Html::a(Html::img($model->getThumbFileUrl('banner', 'xsthumb'), ['class' => 'img-responsive']), $model->url) ?>
    </div>

    <div class="col-xs-8">
        <?= Html::a($model->title, $model->url) ?>
    </div>


</div>
