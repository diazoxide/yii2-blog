<?php

use yii\base\Event;
use yii\helpers\Html;
use diazoxide\blog\Module;

/** @var \diazoxide\blog\models\BlogPost $post */
?>

<article class="blog-post">
    <div class="blog-post__nav">
        <?php if ($post->type->has_category): ?>
            <p class="blog-post__category">
                <?= Module::t('', 'Category'); ?>
                : <?= Html::a($post->category->title, $post->category->url); ?>
            </p>
        <?php endif; ?>

        <p class="blog-post__info">

            <?php /** @var boolean $showDate */
            if ($showDate): ?>
                <time title="<?= Module::t('', 'Create Time'); ?>" itemprop="datePublished"
                      datetime="<?= date_format(date_timestamp_set(new DateTime(), $post->created_at), 'c') ?>">
                    <i class="fa fa-calendar-alt"></i>
                    <?= /** @var String $dateType */
                    Yii::$app->formatter->format($post->created_at, $dateType); ?>
                </time>
            <?php endif; ?>

            <?php /** @var boolean $showClicks */
            if ($showClicks): ?>
                <span title="<?= Module::t('', 'Click'); ?>"><i class="fa fa-eye"></i> <?= $post->click; ?></span>
            <?php endif; ?>

            <?php if ($post->tagLinks): ?>
                <span title="<?= Module::t('', 'blog', 'Tags'); ?>">
                        <i class="fa fa-tag"></i> <?= implode(' ', $post->tagLinks); ?>
                    </span>
            <?php endif; ?>
        </p>
    </div>

    <?php if ($post->type->has_banner && $post->banner && $post->module->showBannerInPost) : ?>
        <div class="blog-post__img">
            <img src="<?= $post->getThumbFileUrl('banner', 'thumb'); ?>"
                 alt="<?= $post->title; ?>" class="img-responsive">
        </div>
    <?php endif; ?>

    <?php if ($post->type->has_title): ?>
        <h1>
            <small><?= Html::encode($post->title); ?></small>
        </h1>
    <?php endif; ?>

    <div id="blog-post-content" class="blog-post__content">

        <?php
        /*
         * Print post content
         * If post type has content
         * */
        if ($post->type->has_content) {

            /*
             * Printing main post content property value
             * */
            echo $post->content;

        }

        /* Printing books */
        echo $this->render('_books', ['books' => $post->getBooks()]);

        ?>
    </div>
</article>
