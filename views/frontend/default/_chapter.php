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

<h5><?= Html::a($model->title, $model->url) ?></h5>
<p class="small"><?= $model->brief ?></p>

