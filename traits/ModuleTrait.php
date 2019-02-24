<?php

namespace diazoxide\yii2blog\traits;

use diazoxide\yii2blog\Module;

trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('blog');
    }

}
