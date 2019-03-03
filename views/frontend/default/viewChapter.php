<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 02.03
 * Time: 19:41
 */

use diazoxide\blog\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

/** @var \diazoxide\blog\models\BlogPostBookChapter $chapter */

$this->title = $chapter->title;
$this->params['breadcrumbs'] = $chapter->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $chapter->title ?></h1>
<h4><?= $chapter->brief ?></h4>

<?php
$dataProvider = new ActiveDataProvider([
    'query' => $chapter->getChapters(),
    'pagination' => [
        'pageSize' => 50,
    ],
]);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_chapter',
    'emptyText' => '',
    'layout' => "{summary}<div class='col-xs-12'>{items}</div>{pager}",
]);
?>

<div>
    <?= $chapter->getParsedContent(); ?>
</div>
