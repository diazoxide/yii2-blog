<?php

namespace diazoxide\blog\traits;


use Yii;

trait ModuleTrait
{
    /**
     * @return  \diazoxide\blog\Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('blog');
    }

}
