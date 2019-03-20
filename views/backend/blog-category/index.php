<?php

use diazoxide\blog\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel diazoxide\blog\models\BlogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('Blog Categories');
/** @var array $breadcrumbs */
$this->params['breadcrumbs'] = $breadcrumbs;
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
            'class' => 'btn btn-default btn-xs',
            'tabindex' => $key,
            'data' => [
                'html' => "true",
                'toggle' => "popover",
                'trigger' => "focus",
                'content' => $menu,
                'placement' => "bottom"
            ]
        ]);

        if($item->children){
        echo Html::beginTag('ul');
        renderItem($item->children);
        echo Html::endTag('ul');
        echo Html::endTag('li');
        }

    }
}

?>
<div class="blog-category-index">

    <p>
        <?= Html::a(Module::t('Create ') . Module::t('Blog Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
