<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */
/* @var $this \yii\web\View */
/* @var $post \diazoxide\blog\models\BlogPost */

/* @var $dataProvider \yii\data\ActiveDataProvider */

use diazoxide\blog\Module;


\diazoxide\blog\assets\AppAsset::register($this);

$this->title = $post->title;


$this->params['breadcrumbs'] = $post->breadcrumbs;
$this->params['breadcrumbs'][] = $this->title;

$schema = (object)[
    "@type" => "http://schema.org/NewsArticle",
    "http://schema.org/headline" => $post->title,
    "http://schema.org/description" => $post->brief,
    "http://schema.org/backstory" => $post->brief,
    "http://schema.org/articleBody" => $post->content,
    "http://schema.org/articleSection" => $post->type->has_category ? $post->category->title : $post->type->title,
    "http://schema.org/dateline" => Module::t('', 'Published: ') . $post->getPublished(),
    "http://schema.org/wordCount" => \yii\helpers\StringHelper::countWords($post->content),
    "http://schema.org/datePublished" => date_format(date_timestamp_set(new DateTime(), $post->published_at), 'c'),
    "http://schema.org/dateModified" => date_format(date_timestamp_set(new DateTime(), $post->updated_at), 'c'),
    "http://schema.org/mainEntityOfPage" => (object)[
        "@type" => "http://schema.org/WebPage",
        '@id' => $post->url
    ],
    "http://schema.org/image" => [
        $post->getImageFileUrl('banner'),
        $post->getThumbFileUrl('banner', 'xsthumb'),
        $post->getThumbFileUrl('banner', 'sthumb'),
        $post->getThumbFileUrl('banner', 'mthumb'),
        $post->getThumbFileUrl('banner', 'xthumb'),
        $post->getThumbFileUrl('banner', 'thumb'),
        $post->getThumbFileUrl('banner', 'thumb'),
    ],
    "http://schema.org/thumbnailUrl" => $post->getThumbFileUrl('banner', 'thumb'),
    "http://schema.org/author" => (object)[
        "@type" => "http://schema.org/Person",
        "http://schema.org/name" => $post->user->{$this->context->module->userName}
    ],
    "http://schema.org/publisher" => $this->context->module->JsonLD->publisher,

];
$this->context->module->JsonLD->add($schema);
$this->context->module->JsonLD->registerScripts();

?>

<div class="row blog-post__wrapper">
    <?= $this->render('_article', [
        'post' => $post,
        'dataProvider' => $dataProvider,
        'showDate' => $showDate,
        'dateType' => $dateType,
        'showClicks' => $showClicks,
    ]) ?>
</div>


<?php if ($post->module->enableShareButtons) : ?>
    <section id="share-box">
        <div class="row">
            <?php if (isset($this->context->module->social['addthis']['pubid'])) : ?>
                <script type="text/javascript"
                        src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $this->context->module->social['addthis']['pubid']; ?>"></script>
                <div class="addthis_inline_share_toolbox_hty0"></div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>


<?php if ($post->module->enableComments && $post->show_comments && $post->type->has_comment) : ?>
    <section id="comments" class="top-buffer-20-xs">

        <div class="row">
            <div class="widget_title"><?= Module::t('', 'Comments'); ?></div>

            <?php if ($post->module->enableFacebookComments): ?>

                <div class="col-sm-12">
                    <?php
                    if (isset($this->context->module->social['facebook']['app_id'])) {
                        echo \diazoxide\blog\widgets\social\FacebookComments::widget(
                            [
                                'app_id' => $this->context->module->social['facebook']['app_id'],
                                'data' => ['width' => '100%', 'numposts' => '5']
                            ]
                        );
                    }
                    ?>
                </div>


            <?php endif; ?>

            <?php if ($post->module->enableLocalComments) : ?>

                <div class="col-sm-12">
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_comment',
                        'viewParams' => [
                            'post' => $post,
                        ],
                    ]) ?>
                </div>

                <div class="col-sm-12">
                    <h3><?= Module::t('', 'Write comments'); ?></h3>
                    <?= $this->render('_form', [
                        'model' => $comment,
                    ]); ?>
                </div>

            <?php endif; ?>
        </div>

    </section>
<?php endif; ?>

<section id="related-posts">
    <?= \diazoxide\blog\widgets\Feed::widget([
        'items_count' => 3,
        'options' => ['tag' => 'div', 'class' => ''],
        'header_options' => ['tag' => 'div', 'class' => 'row'],
        'category_id' => $post->category_id,
        'show_title' => true,
        'title_options' => ['class' => 'widget_title'],
        'title' => Module::t('', 'Related Posts'),
        'show_item_brief' => false,
        'body_options' => ['class' => 'row'],
        'show_item_category_icon' => false,
        'item_brief_length' => 50,
        'item_options' => ['class' => 'col-md-4 col-xs-12 top-buffer-20-xs'],
        'item_image_container_options' => ['class' => 'col-xs-4 col-md-12'],
        'item_content_container_options' => ['class' => 'col-xs-8 col-md-12'],
        'item_date_type' => 'dateTime',
        'item_info_container_options' => ['class' => 'text-warning text-right small'],
        'id' => 'related_post_widget'
    ]);
    ?>
</section>

