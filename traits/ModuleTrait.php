<?php

namespace diazoxide\blog\traits;


trait ModuleTrait
{
    /**
     * @return  \diazoxide\blog\Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('blog');
    }

}
