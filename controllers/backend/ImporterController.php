<?php

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\importer\Wordpress;
use diazoxide\blog\Module;
use Yii;
use yii\base\Controller;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yiidreamteam\upload\exceptions\FileUploadException;

/**
 * @property Module module
 */
class ImporterController extends Controller
{

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionWordpress()
    {
        $model = new Wordpress();
        if ($model->load(Yii::$app->request->get())) {

            if ($model->validate()) {
                Yii::$app->session->setFlash('success', Module::t('', "Posts import in progress."));
                Yii::$app->session->setFlash('warning', print_r($model->getEndpoint('posts', ['page' => 2, 'per_page' => 5]), true));

            }
        }
        return $this->render('wordpress', [
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
