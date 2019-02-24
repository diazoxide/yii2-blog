<?php
// _list_item.php
use diazoxide\blog\Module;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<article class="item row" data-key="<?= $model->id; ?>">

    <div class="col-xs-3 col-md-12">
        <div class="post-thumbnail">
            <a href="<?= $model->url ?>" class="post_image">
                <?= Html::img($model->getThumbFileUrl('banner', $imageType), ['class' => 'img-responsive']) ?>
            </a>
        </div>

    </div>

    <div class="col-xs-9 col-md-12">


        <h5><?= Html::a(\yii\helpers\StringHelper::truncate(Html::encode($model->title), 50, "..."), $model->url) ?></h5>

        <?php if ($showBrief) {
            echo Html::tag('p', yii\helpers\StringHelper::truncate(Html::encode($model->brief), $briefLength, '...'), ['class' => 'small']);
        } ?>


        <p>

            <span>
                <i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDate($model->created_at); ?>
            </span>

            <?php /** @var boolean $showCategoryTitle */
            if ($showCategoryTitle): ?>
                <span class="label label-warning"><?= $model->category->title; ?></span>
            <?php endif; ?>

            <?php /** @var boolean $showViews */
            if ($showViews): ?>
                <span><i class="fa fa-eye"></i> <?= $model->click; ?></span>
            <?php endif; ?>


        </p>
        <p class="text-right">
            <?php /** @var boolean $showReadMoreButton */
            if ($showReadMoreButton) {
                echo Html::a($readMoreText, $model->url, ['class' => 'btn btn-default']);
            } ?>
        </p>


    </div>


</article>