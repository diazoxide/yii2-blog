<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<article class="item row" data-key="<?= $model->id; ?>">

    <div class="col-sm-12">
        <a href="<?= $model->url ?>">
            <img class="img-responsive pull-left thumbnail" src="<?= $model->getThumbFileUrl('banner', 'thumb'); ?>"/>
        </a>
    </div>

    <div class="col-sm-12">
        <a href="<?= $model->url ?>">
            <?= \yii\helpers\StringHelper::truncate(Html::encode($model->title), 50, "...") ?>
        </a>

        <?php if($showBrief){
            echo Html::tag('p',yii\helpers\StringHelper::truncate(Html::encode($model->brief),$briefLength,'...'));
        }?>

        <p>

            <span class="label label-warning"><?= $model->category->title; ?></span>

            <span>
                <i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDate($model->created_at); ?>
            </span>

        </p>


    </div>


</article>