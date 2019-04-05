<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use yii\base\Event;
use yii\helpers\Html;
use diazoxide\blog\Module;

/** @var \diazoxide\blog\models\BlogPost $post */
?>

<article class="blog-post" itemscope itemtype="http://schema.org/Article">

    <meta itemprop="author" content="<?= $post->user->{$this->context->module->userName}; ?>">
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
            <?= Module::t('Category'); ?>
            : <?= Html::a($post->category->title, $post->category->url); ?>
        </p>
        <p class="blog-post__info">

            <?php /** @var boolean $showDate */
            if ($showDate): ?>
                <time title="<?= Module::t('Create Time'); ?>" itemprop="datePublished"
                      datetime="<?= date_format(date_timestamp_set(new DateTime(), $post->created_at), 'c') ?>">
                    <i class="fa fa-calendar-alt"></i>
                    <?= /** @var String $dateType */
                    Yii::$app->formatter->format($post->created_at, $dateType); ?>
                </time>
            <?php endif; ?>

            <?php /** @var boolean $showClicks */
            if ($showClicks): ?>
                <span title="<?= Module::t('Click'); ?>"><i class="fa fa-eye"></i> <?= $post->click; ?></span>
            <?php endif; ?>

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

    <h1 itemprop="headline">
        <small><?= Html::encode($post->title); ?></small>
    </h1>

    <?php /* Before post content Event */
    $this->context->module->trigger(
        $this->context->module::EVENT_BEFORE_POST_CONTENT_VIEW,
        new Event(['sender' => $this, 'data' => ['post' => $post]])
    );
    ?>
    <div id="blog-post-content" class="blog-post__content" itemprop="articleBody">

        <?php
        /* Print post content*/
        echo $post->content;

        /* Before books */
        $this->context->module->trigger(
            $this->context->module::EVENT_BEFORE_POST_BOOK_VIEW,
            new Event(['sender' => $this, 'data' => ['post' => $post]])
        );

        /* Printing books */
        echo $this->render('_books', ['books' => $post->getBooks()]);

        /* After books event*/
        $this->context->module->trigger(
            $this->context->module::EVENT_AFTER_POST_BOOK_VIEW,
            new Event(['sender' => $this, 'data' => ['post' => $post]])
        );
        ?>
    </div>
    <?php /* After post content event*/
    $this->context->module->trigger(
        $this->context->module::EVENT_AFTER_POST_CONTENT_VIEW,
        new Event(['sender' => $this, 'data' => ['post' => $post]])
    ); ?>

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
