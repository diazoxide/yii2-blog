<?php

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\Module;
use yii\web\Response;

/**
 * @property Module module
 */
class ImperaviController extends BaseAdminController
{

    /**
     * @return array
     */
    public function actions()
    {

        return [
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => $this->module->imgFileUrl, // Directory URL address, where files are stored.
                'path' => $this->module->imgFilePath,
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => $this->module->imgFileUrl, // Directory URL address, where files are stored.
                'path' => $this->module->imgFilePath,
            ],
            'file-delete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => $this->module->imgFileUrl, // Directory URL address, where files are stored.
                'path' => $this->module->imgFilePath,
            ],
            'files-get' => [
                'class' => 'vova07\imperavi\actions\GetFilesAction',
                'url' => $this->module->imgFileUrl, // Directory URL address, where files are stored.
                'path' => $this->module->imgFilePath,
            ],
            'file-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => $this->module->imgFileUrl, // Directory URL address, where files are stored.
                'path' => $this->module->imgFilePath,
                'uploadOnlyImage' => false, // For any kind of files uploading.
            ],
        ];
    }

}
