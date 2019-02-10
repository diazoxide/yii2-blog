<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<article class="item row" data-key="<?= $model->id; ?>">

    <div class="col-sm-3">
        <a href="<?= $model->url ?>">
            <img class="img-responsive pull-left thumbnail" src="<?= /** @var BlogPos $model */
            $model->getThumbFileUrl('banner', 'thumb'); ?>"/>
        </a>
    </div>

    <div class="col-sm-9 nopadding">
        <a href="<?= $model->url ?>">
            <?= \yii\helpers\StringHelper::truncate(Html::encode($model->title), 50, "...") ?>
        </a>

        <?php /** @var boolean $showBrief */
        if ($showBrief) {
            /** @var int $briefLength */
            echo Html::tag('p', yii\helpers\StringHelper::truncate(Html::encode($model->brief), $briefLength, '...'));
        } ?>

        <p>
            <?php if ($showCategory): ?>
                <span class="label label-warning"><?= $model->category->titleWithIcon; ?></span>
            <?php endif; ?>

            <?php if ($showDate): ?>

                <span><i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDate($model->created_at); ?></span>
            <?php endif; ?>

            <?php if ($showViews): ?>

                <span><i class="fa fa-eye"></i> <?= $model->click; ?></span>
            <?php endif; ?>

        </p>


    </div>


</article>