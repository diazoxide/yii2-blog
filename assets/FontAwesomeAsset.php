<?php

namespace diazoxide\blog\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';
    public $css = [
        'css/all.min.css',
        'css/v4-shims.css',
    ];
}
