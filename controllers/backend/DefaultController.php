<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\Module;
use Yii;

/**
 * @property Module module
 */
class DefaultController extends BaseAdminController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect("user/login");
        }
        return $this->render('index');
    }
}
