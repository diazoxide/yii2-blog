<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\frontend;

use diazoxide\blog\models\BlogPostData;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogComment;
use diazoxide\blog\models\BlogCommentSearch;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostBook;
use diazoxide\blog\models\BlogPostBookChapter;
use diazoxide\blog\models\BlogPostBookChapterSearch;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\ModuleTrait;

/**
 * @property Module module
 */
class FixerController extends Controller
{
    /**
     * @param $slug
     * @return \yii\web\Response
     */
    public function actionSlug($slug)
    {
        $post = BlogPost::findOne(['slug' => $slug]);
        return $this->redirect($post->url);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionId($id)
    {
        $post = BlogPost::findOne($id);
        return $this->redirect($post->url);
    }


    public function actionWordpress($id){
        $opt = BlogPostData::findOne(['name'=>"wp_" . md5("https://armday.org")."_origin_id",'value'=>$id]);
        $post = BlogPost::findOne($opt->owner_id);
        return $this->redirect($post->url);
    }
}
