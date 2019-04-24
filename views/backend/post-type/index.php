<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 23.04
 * Time: 15:05
 */

use \yii\grid\GridView;
use \yii\helpers\Html;
use \diazoxide\blog\Module;

?>

<p>
    <?= Html::a(Module::t('', 'Create ') . Module::t('', 'Post Type'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataprovider,
    'columns' => [
        'id',
        'title',
        ['class' => 'yii\grid\ActionColumn']
    ],
]); ?>
