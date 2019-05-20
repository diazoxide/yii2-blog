<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;

class DynamicFrontendAsset extends AssetBundle
{
    public $sourcePath = '@vendor/diazoxide/yii2-blog/assets/default';

    public $baseUrl = '@web';

    public $css = [
//        'css/style.css',
        'css/bootstrap_custom.css',
        'css/common.css',
    ];

    public $js = [
    ];

    public $depends = [
        BootstrapAsset::class,
        StickySidebarAsset::class,
        FontAwesomeAsset::class,
        FeatherlightAsset::class
    ];
}
