<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\assets;

use Yii;
use yii\web\AssetBundle;

class TinymceFileManagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/diazoxide/yii2-blog/assets/tinymce_file_manager';

    public $baseUrl = '@web';

    public $css = [

    ];

    public $js = [
    ];

    public $depends = [

    ];

    public static function getAssetUrl($asset)
    {
        $view = Yii::$app->getView();
        $assetManager = $view->getAssetManager();
        $bundle = $assetManager->getBundle(self::class);
        return $assetManager->getAssetUrl($bundle, $asset);
    }
}
