<?php

namespace diazoxide\blog\widgets\social;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class FacebookComments extends Widget
{
    public $app_id;
    public $data = [];
    public $language = 'en_US';

    public function init()
    {
        if (!isset($this->data['href'])) {
            $this->data['href'] = Yii::$app->request->getAbsoluteUrl();
        }
        echo '<div id="fb-root"></div><script async defer crossorigin="anonymous" src="https://connect.facebook.net/' . $this->language . '/sdk.js#xfbml=1&version=v3.2&appId=' . $this->app_id . '&autoLogAppEvents=1"></script>';

    }

    public function run()
    {
        echo Html::tag('div', null, ['class' => 'fb-comments', 'data' => $this->data]);
    }
}