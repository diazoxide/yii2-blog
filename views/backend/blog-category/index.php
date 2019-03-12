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
?>
<div class="blog-category-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a(Module::t('Create ') . Module::t('Blog Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
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
        <?php foreach ($dataProvider as $item) { ?>
            <tr data-key="1">
                <td><?= $item['id']; ?></td>
                <td><?= Html::img($item['banner'], ['class' => 'img-responsive', 'width' => 100]); ?></td>
                <td><?= $item['str_label']; ?></td>
                <td><?= $item['sort_order']; ?></td>
                <td><?= $item['template']; ?></td>
                <td><?= BlogCategory::getOneIsNavLabel($item['is_nav']); ?></td>
                <td><?= $item['status']; ?></td>
                <td>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/create', 'parent_id' => $item['id']]); ?>" title="<?= Module::t('Add Sub Catelog'); ?>"
                       data-pjax="0"><span class="glyphicon glyphicon-plus-sign"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/view', 'id' => $item['id']]); ?>"" title="<?= Module::t('View'); ?>" data-pjax="0"><span
                        class="glyphicon glyphicon-eye-open"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/update', 'id' => $item['id']]); ?>"" title="<?= Module::t('Update'); ?>" data-pjax="0"><span
                        class="glyphicon glyphicon-pencil"></span></a>
                    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['blog/blog-category/delete', 'id' => $item['id']]); ?>"" title="<?= Module::t('Delete'); ?>"
                    data-confirm="<?= Module::t('Are you sure you want to delete this item?'); ?>" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>
