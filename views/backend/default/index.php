<?php

use diazoxide\blog\Module;
use yii\helpers\Html;

?>

<div class="col-md-3">
    <?php
    $model = \diazoxide\blog\models\BlogCategory::find()->limit(10)->orderBy(['sort_order' => SORT_DESC]);
    echo Html::a('<i class="fa fa-list"></i> ' . Module::t('blog', 'Blog Categories') . ' (' . $model->count() . ')', ['/blog/blog-post']);
    ?>
    <ul>
        <?php
        /** @var \diazoxide\blog\models\BlogCategory $item */
        foreach ($model->all() as $item) {
            echo Html::tag(
                'li', Html::a($item->titleWithIcon, $item->url),
                ['class' => '']
            );
        }
        ?>
    </ul>
    <?= Html::a(Module::t('blog', 'See All'), ['/blog/blog-category'], ['class' => 'btn btn-warning']); ?>

</div>
<div class="col-md-3">
    <?php
    $model = \diazoxide\blog\models\BlogPost::find()->limit(10)->orderBy(['id' => SORT_DESC]);
    echo Html::a('<i class="fa fa-newspaper-o"></i> ' . Module::t('blog', 'Blog Posts') . ' (' . $model->count() . ')', ['/blog/blog-post']);
    ?>
    <ul>
        <?php
        /** @var \diazoxide\blog\models\BlogPost $item */
        foreach ($model->all() as $item) {
            echo Html::tag(
                'li',
                Html::a(
                        Html::img($item->getThumbFileUrl('banner', 'xsthumb'), ['width' => '16px', 'style' => 'float:left']) . ' ' .
                    yii\helpers\StringHelper::truncate(Html::encode($item->title), 30, '...'),
                    $item->url),
                ['class' => '']
            );
        }
        ?>
    </ul>
    <?= Html::a(Module::t('blog', 'See All'), ['/blog/blog-post'], ['class' => 'btn btn-warning']); ?>

</div>
<div class="col-md-3">
    <i class="fa fa-comment"></i>
    <?= Html::a(Module::t('blog', 'Blog Comments'), ['/blog/blog-comment']); ?>
</div>
<div class="col-md-3">
    <i class="fa fa-tags"></i>
    <?= Html::a(Module::t('blog', 'Blog Tags'), ['/blog/blog-tag']); ?>
</div>
