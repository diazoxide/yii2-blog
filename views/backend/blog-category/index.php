<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel diazoxide\blog\models\BlogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('Blog Categorys');
$this->params['breadcrumbs'][] = $this->title;

function renderItem($model)
{
    foreach ($model as $key => $item) {

        echo Html::beginTag('li');
        echo Html::a($item->title, $item->url);

        $menu = Html::beginTag('span', ['class' => 'btn-group']);
        $menu .= Html::a('<i class="fa fa-eye"></i>', ['blog-category/view', 'id' => $item->id], ['class' => 'btn btn-default btn-sm', 'title' => Module::t('View Category')]);
        $menu .= Html::a('<i class="fa fa-plus"></i>', ['blog-category/create', 'parent_id' => $item->id], ['class' => 'btn btn-success btn-sm', 'title' => Module::t('Add Sub Category')]);
        $menu .= Html::a('<i class="fa fa-pencil"></i>', ['blog-category/update', 'id' => $item->id], ['class' => 'btn btn-warning btn-sm', 'title' => Module::t('Update Category')]);
        $menu .= Html::a('<i class="fa fa-remove"></i>', ['blog-category/delete', 'id' => $item->id], ['class' => 'btn btn-danger btn-sm', 'title' => Module::t('Delete Category'), 'data-confirm' => Module::t('Are you sure you want to delete this item?'), 'data-method' => 'post', 'data-pjax' => 0]);
        $menu .= Html::endTag('span');

        echo Html::a('<i class="fa fa-plus"></i>', null, [
            'title' => Module::t('Menu'),
            'class' => 'btn btn-default btn-xs left-buffer-10-xs',
            'tabindex' => $key,
            'data' => [
                'html' => "true",
                'toggle' => "popover",
                'trigger' => "focus",
                'content' => $menu,
                'placement'=>"bottom"
            ]
        ]);

        echo Html::beginTag('ul');
        renderItem($item->children);
        echo Html::endTag('ul');

        echo Html::endTag('li');
    }
}

?>
<div class="blog-category-index">

    <p>
        <?= Html::a(Module::t('Create ') . Module::t('Blog Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php /*
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th><?= Module::t('Banner') ?></th>
            <th><?= Module::t('Title') ?> </th>
            <th><?= Module::t('Sort Order') ?></th>
            <th><?= Module::t('Template') ?></th>
            <th><?= Module::t('Is Nav') ?></th>
            <th><?= Module::t('Status') ?></th>
            <th><?= Module::t('Actions') ?></th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($dataProvider->asArray()->all() as $item) { ?>
            <tr data-key="1">
                <td><?= $item['id']; ?></td>
                <td><?= Html::img($item['banner'], ['class' => 'img-responsive', 'width' => 100]); ?></td>
                <td><?= $item['title']; ?></td>
                <td><?= $item['sort_order']; ?></td>
                <td><?= $item['template']; ?></td>
                <td><?= BlogCategory::getOneIsNavLabel($item['is_nav']); ?></td>
                <td><?= $item['status']; ?></td>
                <td>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/create', 'parent_id' => $item['id']]); ?>"
                       title="<?= Module::t('Add Sub Catelog'); ?>"
                       data-pjax="0"><span class="glyphicon glyphicon-plus-sign"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/view', 'id' => $item['id']]); ?>""
                    title="<?= Module::t('View'); ?>" data-pjax="0"><span
                            class="glyphicon glyphicon-eye-open"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/update', 'id' => $item['id']]); ?>""
                    title="<?= Module::t('Update'); ?>" data-pjax="0"><span
                            class="glyphicon glyphicon-pencil"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/delete', 'id' => $item['id']]); ?>""
                    title="<?= Module::t('Delete'); ?>"
                    data-confirm="<?= Module::t('Are you sure you want to delete this item?'); ?>" data-method="post"
                    data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
 */ ?>
    <ul>
        <?php
        renderItem($dataProvider);
        ?>
    </ul>


</div>

<?php
$this->registerJs(<<<HTML
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
HTML
)
?>
