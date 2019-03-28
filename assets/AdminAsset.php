<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@vendor/diazoxide/yii2-blog/assets/default';

    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap_custom.css',
        'css/common.css',
    ];

    public $js = [
        'js/fixRedactor.js'
    ];
    public $depends = [
        \yii\bootstrap\BootstrapAsset::class,
        FontAwesomeAsset::class,
        ModmoreRedactorPluginsAsset::class
    ];
}
