<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@vendor/diazoxide/yii2-blog/assets/default';
    public $baseUrl = '@web';
    public $css = [
        'css/blog-style.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'diazoxide\blog\assets\StickySidebarAsset',
        'diazoxide\blog\assets\StickySidebarAsset',
    ];
}
