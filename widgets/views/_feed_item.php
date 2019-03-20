<?php

use yii\helpers\Html;

/** @var array $imageContainerOptions */
/** @var \diazoxide\blog\models\BlogPost $model */
/** @var array $titleOptions */
/** @var string $titleSuffix */
/** @var string $briefSuffix */
/** @var int $titleLength */
/** @var string $imageType */
/** @var string $imageType */
/** @var boolean $showBrief */
/** @var int $briefLength */
/** @var array $briefOptions */
/** @var array $readMoreButtonOptions */
/** @var String $readMoreButtonText */
/** @var boolean $showReadMoreButton */
/** @var array $contentContainerOptions */
/** @var string $readMoreButtonIconClass */

?>

<?php
/**
 * Building image container Html
 */
echo Html::tag(
    isset($imageContainerOptions['tag']) && !empty($imageContainerOptions['tag']) ? $imageContainerOptions['tag'] : 'div',
    Html::a(
        Html::img(
            $model->getThumbFileUrl('banner', $imageType),
            ['class' => 'img-responsive pull-left']
        ),
        $model->url
    ),
    $imageContainerOptions
);

echo Html::beginTag('div', $contentContainerOptions);

/**
 * Building title Html
 * @var boolean $showTitle
 */
if ($showTitle)

    echo Html::a(
        Html::tag(
            isset($titleOptions['tag']) && !empty($titleOptions['tag']) ? $titleOptions['tag'] : 'div',
            \yii\helpers\StringHelper::truncate(Html::encode($model->title), $titleLength, $titleSuffix),
            $titleOptions
        ),
        $model->url
    );

?>

<?php
/** @var array $infoContainerOptions */
echo Html::beginTag(
    'div',
    $infoContainerOptions);
?>
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
    <span class="blog_datetime">
        <i class="fa fa-calendar"></i>
        <?= /** @var string $dateType */
        Yii::$app->formatter->format($model->created_at, $dateType) ?>
    </span>
<?php endif; ?>
<?php /** @var boolean $showViews */
if ($showViews): ?>
    <span><i class="fa fa-eye"></i> <?= $model->click; ?></span>
<?php endif; ?>

<?php
echo Html::endTag('div');
?>

<?php
/**
 * Building brief content Html
 */
if ($showBrief) {
    echo Html::tag(
        isset($briefOptions['tag']) && !empty($briefOptions['tag']) ? $briefOptions['tag'] : 'div',
        \yii\helpers\StringHelper::truncate(Html::encode($model->brief), $briefLength, $briefSuffix),
        $briefOptions
    );
}

/**
 * Building button html
 */
if ($showReadMoreButton) {

    echo Html::a(
        '<i class="' . $readMoreButtonIconClass . '"></i> ' . \diazoxide\blog\Module::t($readMoreButtonText),
        $model->url,
        $readMoreButtonOptions
    );
}
echo Html::endTag('div');
?>

