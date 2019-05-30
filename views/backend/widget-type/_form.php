<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use diazoxide\blog\Module;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use diazoxide\blog\widgets\Feed;

/* @var $this yii\web\View */
/* @var $model diazoxide\blog\models\BlogWidgetType */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="blog-widget-type-form">

    <?php

    $form = ActiveForm::begin([
        'options' => [],
    ]);
    ?>

    <div class="top-buffer-20-xs text-right">
        <?= Html::submitButton($model->isNewRecord ? Module::t('', 'Create') : Module::t('', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <div class="top-buffer-20-xs">

        <?php
        echo $form->field($model, 'title')->textInput(['maxlength' => 255]);

        echo Tabs::widget([
            'tabContentOptions' => [
                'class' => 'top-buffer-20-xs',
            ],
            'items' => [
                [
                    'label' => 'Main',
                    'content' =>
                        $form->field($model, 'config[type]')->dropDownList([
                            Feed::TYPE_RANDOM => Module::t('', 'Random'),
                            Feed::TYPE_POPULAR => Module::t('', 'Popular'),
                            Feed::TYPE_RECENT => Module::t('', 'Recent'),
                        ])->label(Module::t('', 'Type')) .
                        $form->field($model, 'config[show_title]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Title')) .
                        $form->field($model, 'config[active_title]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Active Title')) .
                        $form->field($model, 'config[active_title_url]')->textInput(['maxlength' => 255])->label(Module::t('', 'Active Title Url')) .
                        $form->field($model, 'config[title]')->textInput(['maxlength' => 255])->label(Module::t('', 'Widget Title')) .
                        $form->field($model, 'config[days_interval]')->textInput(['type' => 'number'])->label(Module::t('', 'Days Interval')) .
                        $form->field($model, 'config[show_category_title]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Category Title')) .
                        $form->field($model, 'config[show_category_filter]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Category Filter')) .
                        $form->field($model, 'config[infinite_scroll]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Infinite Scroll')) .
                        $form->field($model, 'config[show_pager]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Pager')) .
                        $form->field($model, 'config[load_more_button]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Load More Button')),
                    'active' => true,
                    'headerOptions' => [
                        'id' => 'main',
                    ],
                    'options' => [
                        'href' => '#main'
                    ]
                ],
                [
                    'label' => 'Items Configuration',
                    'items' => [
                        [
                            'label' => 'Views',
                            'content' =>
                                $form->field($model, 'config[show_item_read_more_button]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Read More Button')) .
                                $form->field($model, 'config[items_count]')->textInput(['type' => 'number'])->label(Module::t('', 'Items Count')) .
                                $form->field($model, 'config[offset]')->textInput(['type' => 'number'])->label(Module::t('', 'Offset')) .
                                $form->field($model, 'config[item_image_type]')->textInput(['maxlength' => 60])->label(Module::t('', 'Item Image Type')) .
                                $form->field($model, 'config[item_read_more_button_text]')->textInput(['maxlength' => 60])->label(Module::t('', 'Item Read More Button Text')) .
                                $form->field($model, 'config[item_read_more_button_icon_class]')->textInput(['maxlength' => 60])->label(Module::t('', 'Item Read More Button Icon Class')) .
                                $form->field($model, 'config[show_item_category]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Category')) .
                                $form->field($model, 'config[show_item_category_icon]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Category Icon')) .
                                $form->field($model, 'config[show_item_category_with_icon]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Category With Icon')) .
                                $form->field($model, 'config[show_item_views]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Views')) .
                                $form->field($model, 'config[show_item_date]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Date')) .
                                $form->field($model, 'config[item_date_type]')->dropDownList(
                                    [
                                        'relativeTime' => Module::t('', 'Relative time'),
                                        'time' => Module::t('', 'Time'),
                                        'date' => Module::t('', 'Date'),
                                        'dateTime' => Module::t('', 'Date and time')
                                    ]
                                )->label(Module::t('', 'Item Date Format')),
                        ],
                        [
                            'label' => 'Title',
                            'content' =>
                                $form->field($model, 'config[show_item_title]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Title')) .
                                $form->field($model, 'config[item_title_length]')->textInput(['type' => 'number'])->label(Module::t('', 'Item Title Length')) .
                                $form->field($model, 'config[item_title_suffix]')->textInput(['maxlength' => 60])->label(Module::t('', 'Item Title Suffix')),
                        ],
                        [
                            'label' => 'Brief',
                            'content' =>
                                $form->field($model, 'config[show_item_brief]')->dropDownList([0 => Module::t('', 'No'), 1 => Module::t('', 'Yes')])->label(Module::t('', 'Show Item Brief')) .
                                $form->field($model, 'config[item_brief_length]')->textInput(['type' => 'number'])->label(Module::t('', 'Item Brief Length')) .
                                $form->field($model, 'config[item_brief_suffix]')->textInput(['maxlength' => 60])->label(Module::t('', 'Item Brief Suffix')),
                        ],

                    ],
                ],
                [
                    'label' => 'HTML Setting',
                    'items' => [
                        [
                            'label' => 'Main Widget',
                            'content' => $form->field($model, 'config[options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style')),
                        ],
                        [
                            'label' => 'Header',
                            'content' => $form->field($model, 'config[header_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[header_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[header_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[header_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Body',
                            'content' => $form->field($model, 'config[body_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[body_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[body_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[body_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Title',
                            'content' => $form->field($model, 'config[title_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[title_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[title_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[title_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Active Title',
                            'content' => $form->field($model, 'config[active_title_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[active_title_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[active_title_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'List',
                            'content' => $form->field($model, 'config[list_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[list_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[list_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[list_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style')),
                        ],
                        [
                            'label' => 'Item',
                            'content' => $form->field($model, 'config[item_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_options][Style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style')),
                        ],
                        [
                            'label' => 'Item Body',
                            'content' => $form->field($model, 'config[item_body_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_body_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_body_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_body_options][Style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style')),
                        ],
                        [
                            'label' => 'Item Image',
                            'content' => $form->field($model, 'config[item_image_container_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_image_container_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_image_container_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_image_container_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style')),
                        ],
                        [
                            'label' => 'Item Content',
                            'content' => $form->field($model, 'config[item_content_container_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_content_container_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_content_container_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_content_container_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Item Info Container',
                            'content' => $form->field($model, 'config[item_info_container_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_info_container_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_info_container_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_info_container_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Item Title',
                            'content' => $form->field($model, 'config[item_title_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_title_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_title_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_title_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Item Brief',
                            'content' =>
                                $form->field($model, 'config[item_brief_options][tag]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Tag')) .
                                $form->field($model, 'config[item_brief_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_brief_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_brief_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                        [
                            'label' => 'Item Read More Button',
                            'content' => $form->field($model, 'config[item_read_more_button_options][id]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML ID')) .
                                $form->field($model, 'config[item_read_more_button_options][class]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Class')) .
                                $form->field($model, 'config[item_read_more_button_options][style]')->textInput(['maxlength' => 255])->label(Module::t('', 'HTML Style'))
                        ],
                    ]
                ],
                [
                    'label' => 'Custom Code',
                    'items' => [
                        [
                            'label' => 'CSS',
                            'content' => $form->field($model, 'config[custom_css]')->textarea(['rows' => '20'])->label(Module::t('', 'CSS Code')),
                        ],
                        [
                            'label' => 'JavaScript',
                            'content' => $form->field($model, 'config[custom_js]')->textarea(['rows' => '20'])->label(Module::t('', 'JS Code')),
                        ]
                    ],
                ]
            ],
        ]);
        ?>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>

<?php
$js = <<<js
$(function(){
  var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
} 

// Change hash for page-reload
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
})

});
js;

$this->registerJs($js); ?>
