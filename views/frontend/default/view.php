<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */
/* @var $this \yii\web\View */
/* @var $post \diazoxide\yii2blog\models\BlogPost */

/* @var $dataProvider \yii\data\ActiveDataProvider */

use diazoxide\yii2blog\Module;
use yii\helpers\Html;
use kartik\social\FacebookPlugin;

\diazoxide\yii2blog\assets\AppAsset::register($this);

$this->title = $post->title;


$this->params['breadcrumbs'][] = [
    'label' => Module::t('blog', 'Blog'),
    'url' => ['default/index']
];
$this->params['breadcrumbs'][] = ['label' => $post->category->title, 'url' => ['default/index', 'category_id' => $post->category->id, 'slug' => $post->category->slug]];
$this->params['breadcrumbs'][] = yii\helpers\StringHelper::truncate(Html::encode($this->title), 30, '...');
$post_user = $post->user;
$username_attribute = Module::getInstance()->userName;
?>
<div class="row blog-post__wrapper">
    <article class="blog-post" itemscope itemtype="http://schema.org/Article">
        <meta itemprop="author" content="<?= $post_user->{$username_attribute}; ?>">
        <meta itemprop="dateModified"
              content="<?= date_format(date_timestamp_set(new DateTime(), $post->updated_at), 'c') ?>"/>
        <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage"
              itemid="<?= $post->getAbsoluteUrl(); ?>"/>
        <meta itemprop="commentCount" content="<?= $dataProvider->getTotalCount(); ?>">
        <meta itemprop="genre" content="<?= $post->category->title; ?>">
        <meta itemprop="articleSection" content="<?= $post->category->title; ?>">
        <meta itemprop="inLanguage" content="<?= Yii::$app->language; ?>">
        <meta itemprop="discussionUrl" content="<?= $post->getAbsoluteUrl(); ?>">
        <div class="blog-post__nav">
            <p class="blog-post__category">
                <?= Module::t('blog', 'Category'); ?>
                : <?= Html::a($post->category->title, $post->category->url); ?>
            </p>
            <p class="blog-post__info">
                <time title="<?= Module::t('blog', 'Create Time'); ?>" itemprop="datePublished"
                      datetime="<?= date_format(date_timestamp_set(new DateTime(), $post->created_at), 'c') ?>">
                    <i class="fa fa-calendar-alt"></i> <?= Yii::$app->formatter->asDateTime($post->created_at); ?>
                </time>
                <span title="<?= Module::t('blog', 'Click'); ?>">
                    <i class="fa fa-eye"></i> <?= $post->click; ?>
                </span>
                <?php if ($post->tagLinks): ?>
                    <span title="<?= Module::t('blog', 'Tags'); ?>">
                        <i class="fa fa-tag"></i> <?= implode(' ', $post->tagLinks); ?>
                    </span>
                <?php endif; ?>
            </p>
        </div>
        <?php if ($post->banner) : ?>
            <div itemscope itemprop="image" itemtype="http://schema.org/ImageObject" class="blog-post__img">
                <?php if ($post->module->showBannerInPost): ?>
                    <img itemprop="url contentUrl" src="<?= $post->getThumbFileUrl('banner', 'thumb'); ?>"
                         alt="<?= $post->title; ?>" class="img-responsive">
                <?php endif; ?>
                <meta itemprop="url" content="<?= $post->getThumbFileUrl('banner', 'thumb'); ?>">
                <meta itemprop="width" content="400">
                <meta itemprop="height" content="300">
            </div>
        <?php endif; ?>
        <h1 class="blog-post__title title title--1" itemprop="headline">
            <?= Html::encode($post->title); ?>
        </h1>

        <div class="blog-post__content" itemprop="articleBody">
            <?php
            echo $post->content;
            ?>
        </div>
        <?php if (isset($post->module->schemaOrg) && isset($post->module->schemaOrg['publisher'])) : ?>
            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" class="blog-post__publisher">
                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                    <meta itemprop="url image"
                          content="<?= Yii::$app->urlManager->createAbsoluteUrl($post->module->schemaOrg['publisher']['logo']); ?>"/>
                    <meta itemprop="width" content="<?= $post->module->schemaOrg['publisher']['logoWidth']; ?>">
                    <meta itemprop="height" content="<?= $post->module->schemaOrg['publisher']['logoHeight']; ?>">
                </div>
                <meta itemprop="name" content="<?= $post->module->schemaOrg['publisher']['name'] ?>">
                <meta itemprop="telephone" content="<?= $post->module->schemaOrg['publisher']['phone']; ?>">
                <meta itemprop="address" content="<?= $post->module->schemaOrg['publisher']['address']; ?>">
            </div>
        <?php endif; ?>
    </article>
</div>


<?php if ($post->module->enableShareButtons) : ?>
    <section id="share" class="blog-share">
        <h2 class="blog-share__header title title--2"><?= Module::t('blog', 'Share'); ?></h2>

        <div class="row">
            <div class="col-md-12">
                <?php if ($post->module->addthisId) : ?>
                    <script type="text/javascript"
                            src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $post->module->addthisId; ?>"></script>
                    <div class="addthis_inline_share_toolbox_hty0"></div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section id="blog-post__bottom_ad">
    <?php
    $banners = Yii::$app->params['middle_banners'];
    $banner = $banners[(rand(0, (count($banners) - 1)))];
    echo Html::a(Html::img($banner['src'], ['class' => 'img-responsive', 'style' => 'width:100%']), $banner['href']);
    ?>
</section>

<?php if ($post->module->enableComments) : ?>
    <section id="comments" class="blog-comments">
        <h2 class="blog-comments__header title title--2"><?= Module::t('blog', 'Comments'); ?></h2>

        <?php if ($post->module->enableFacebookComments): ?>
            <div class="row">
                <div class="col-sm-12">
                    <?= FacebookPlugin::widget(
                        ['type' => FacebookPlugin::COMMENT, 'settings' => [/*'data-width' => '100%',*/
                            'width' => '100%', 'data-numposts' => 5]]
                    ) ?>
                </div>
            </div>

        <?php endif; ?>

        <?php if ($post->module->enableLocalComments) : ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_comment',
                        'viewParams' => [
                            'post' => $post
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <h3><?= Module::t('blog', 'Write comments'); ?></h3>
                    <?= $this->render('_form', [
                        'model' => $comment,
                    ]); ?>
                </div>
            </div>
        <?php endif; ?>

    </section>
<?php endif; ?>
