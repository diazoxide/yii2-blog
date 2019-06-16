<?php

namespace diazoxide\blog\traits;

use diazoxide\blog\Module;
use Yii;

trait ModuleTrait
{
    /**
     * @return  Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('blog');
    }

}
