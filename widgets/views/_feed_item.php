<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= Html::tag(
    'div',
    Html::a(
        Html::img(
            $model->getThumbFileUrl('banner', $imageType),
            ['class' => 'img-responsive pull-left']
        ),
        $model->url
    ),
    $imageContainerOptions
) ?>

<?= Html::beginTag('div', $contentContainerOptions) ?>

<p class="small text-right">
    <?php /** @var boolean $showCategory */
    if ($showCategory): ?>
        <span class="label label-warning"><?= $model->category->title; ?></span>
    <?php /** @var boolean $showCategoryWithIcon */
    elseif ($showCategoryWithIcon): ?>
        <span class="label label-warning"><?= $model->category->titleWithIcon; ?></span>
    <?php /** @var boolean $showCategoryIcon */
    elseif ($showCategoryIcon): ?>
        <span class="label label-warning"><?= $model->category->icon; ?></span>
    <?php endif; ?>
    <?php /** @var boolean $showDate */
    if ($showDate): ?>
        <span class="blog_datetime"><i
                    class="fa fa-calendar"></i> <?=
            Yii::$app->formatter->format($model->created_at, 'relativeTime') ?></span>
    <?php endif; ?>
    <?php /** @var boolean $showViews */
    if ($showViews): ?>
        <span><i class="fa fa-eye"></i> <?= $model->click; ?></span>
    <?php endif; ?>

</p>

<h5 class="nospaces-xs"><?= Html::a(\yii\helpers\StringHelper::truncate(Html::encode($model->title), 50, "..."), $model->url) ?></h5>

<?php /** @var boolean $showBrief */
if ($showBrief) {
    /** @var int $briefLength */
    echo Html::tag('p', yii\helpers\StringHelper::truncate(Html::encode($model->brief), $briefLength, '...'));
} ?>



<?= Html::endTag('div') ?>

