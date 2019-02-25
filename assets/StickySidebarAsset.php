<?php

namespace diazoxide\blog\assets;

use yii\web\AssetBundle;

class StickySidebarAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/sticky-sidebar/src';

    public $js = [
        [
            'sticky-sidebar.js',
            'type' => 'module'

        ],
    ];
}
