<?php
use yii\base\Event;
use yii\helpers\Html;
use diazoxide\blog\Module;
/** @var \diazoxide\blog\models\BlogPost $post */
?>

<article class="blog-post">
    <div class="blog-post__nav">
        <p class="blog-post__category">
            <?= Module::t('', 'Category'); ?>
            : <?= Html::a($post->category->title, $post->category->url); ?>
        </p>
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
    <?php if ($post->banner) : ?>
        <div class="blog-post__img">
            <?php if ($post->module->showBannerInPost): ?>
                <img src="<?= $post->getThumbFileUrl('banner', 'thumb'); ?>"
                     alt="<?= $post->title; ?>" class="img-responsive">
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h1>
        <small><?= Html::encode($post->title); ?></small>
    </h1>

    <?php /* Before post content Event */
    $this->context->module->trigger(
        $this->context->module::EVENT_BEFORE_POST_CONTENT_VIEW,
        new Event(['sender' => $this, 'data' => ['post' => $post]])
    );
    ?>
    <div id="blog-post-content" class="blog-post__content">

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
</article>
