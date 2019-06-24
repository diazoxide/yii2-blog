<?php

namespace diazoxide\blog\widgets\social;

use diazoxide\blog\widgets\assets\AddThisAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class AddThis extends Widget
{
    public $language = 'en_US';

    public $pub_id = '';
    public $width = "320";
    public $height = "92";
    public $layout = "responsive";
    public $data = [
        'widget-id' => 'mohq',
        'widget-type' => 'floating'
    ];

    public function init()
    {

        $this->data['pub-id'] = $this->pub_id;

        $this->view->registerJsFile('https://cdn.ampproject.org/v0/amp-addthis-0.1.js',
            ['position' => yii\web\View::POS_HEAD]);
    }

    public function run()
    {
        echo Html::tag('amp-addthis', '', [
            'width' => $this->width,
            'height' => $this->height,
            'data' => $this->data,
        ]);
    }
}