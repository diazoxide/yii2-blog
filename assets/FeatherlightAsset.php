<?php

namespace diazoxide\blog\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class FeatherlightAsset extends AssetBundle
{
    public $sourcePath = '@bower/featherlight';

    public $css = [
        'release/featherlight.min.css',
    ];
    public $js = [
        'release/featherlight.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
