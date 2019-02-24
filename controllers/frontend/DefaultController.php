<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

namespace diazoxide\yii2blog\controllers\frontend;

use diazoxide\yii2blog\models\BlogCategory;
use diazoxide\yii2blog\models\BlogComment;
use diazoxide\yii2blog\models\BlogCommentSearch;
use diazoxide\yii2blog\models\BlogPost;
use diazoxide\yii2blog\models\BlogPostSearch;
use diazoxide\yii2blog\Module;
use diazoxide\yii2blog\traits\IActiveStatus;
use diazoxide\yii2blog\traits\ModuleTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'lesha724\MathCaptcha\MathCaptchaAction',
            ],
        ];
    }


    public function actionIndex(){


        return $this->render('home', [

        ]);

    }


    public function actionArchive()
    {
        $searchModel = new BlogPostSearch();

        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;
        if(Yii::$app->request->getQueryParam('slug')) {
            $category = BlogCategory::findOne(['slug' => Yii::$app->request->getQueryParam('slug')]);
            $searchModel->category_id = $category->id;

        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'title' => isset($category) ? $category->title : "Գրառումներ",
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView($slug)
    {
        $this->layout = Yii::$app->controller->module->blogViewLayout;
        $post = BlogPost::find()->where(['status' => IActiveStatus::STATUS_ACTIVE, 'slug' => $slug])->one();
        if ($post === null) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        if (Yii::$app->get('opengraph', false)) {
            Yii::$app->opengraph->set([
                'title' => $post->title,
                'description' => $post->brief,
                'image' => $post->getThumbFileUrl('banner','facebook'),
                'imageWidth' => "600",
                'imageHeight' => "315",
            ]);
        }

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $post->brief
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $post->title
        ]);



        $post->updateCounters(['click' => 1]);

        $searchModel = new BlogCommentSearch();
        $searchModel->scenario = BlogComment::SCENARIO_USER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $post->id);


        $comment = new BlogComment();
        $comment->scenario = BlogComment::SCENARIO_USER;

        if ($comment->load(Yii::$app->request->post()) && $post->addComment($comment)) {
            Yii::$app->session->setFlash('success', Module::t('blog', 'A comment has been added and is awaiting validation'));
            return $this->redirect(['view', 'id' => $post->id, '#' => $comment->id]);
        }

        return $this->render('view', [
            'post' => $post,
            'dataProvider' => $dataProvider,
            'comment' => $comment,
        ]);
    }
}
