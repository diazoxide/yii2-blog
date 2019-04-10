<?php

namespace diazoxide\blog\controllers\backend;

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
class TinymceController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload' => ['POST'],
                ],
            ],

        ];
    }

    /**
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpload()
    {
        $imageFolder = Yii::getAlias($this->module->imgFilePath . '/' . $this->module->postContentImagesDirectory);
        $imageUrl = Yii::getAlias($this->module->imgFileUrl . '/' . $this->module->postContentImagesDirectory);
        $temp = UploadedFile::getInstanceByName('file');

        if ($temp) {

            $name = Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d-H-i-s') . '_' . $temp->baseName . '.' . $temp->extension;

            $temp->saveAs($imageFolder . '/' . $name);

            $fileurl = $imageUrl . '/' . $name;

            return json_encode(['location' => $fileurl]);

        } else {

            throw new BadRequestHttpException("Invalid upload.");

        }
    }

}
