<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

namespace app\modules\blog\controllers\backend;

use Yii;

class DefaultController extends BaseAdminController
{
    public function actionIndex()
    {
        //if(!Yii::$app->user->can('readPost')) throw new HttpException(403, 'No Auth');

        if (Yii::$app->user->isGuest) {
            $this->redirect("user/login");
        }
        return $this->render('index');
    }
}
