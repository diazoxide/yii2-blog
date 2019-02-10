<?php

namespace app\modules\blog\traits;

use app\modules\blog\Module;

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
