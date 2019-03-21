<?php

namespace diazoxide\blog\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ModmoreRedactorPluginsAsset extends AssetBundle
{
    public $sourcePath = '@bower/modmore-redactor-plugins';
    public $js = [
        'dist/redactor-plugins.all.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
