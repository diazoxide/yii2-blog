<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\frontend;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogComment;
use diazoxide\blog\models\BlogCommentSearch;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\ModuleTrait;
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


    public function actionIndex()
    {
        $featuredCategories = BlogCategory::find()->where(['is_featured' => true,'status' => IActiveStatus::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_DESC]);

        return $this->render('index', [
            'title' => $this->getModule()->homeTitle,
            'banners' => $this->getModule()->banners,
            'featuredCategories'=>$featuredCategories,
        ]);

    }


    public function actionArchive()
    {
        $searchModel = new BlogPostSearch();

        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;
        if (Yii::$app->request->getQueryParam('slug')) {
            $category = BlogCategory::findOne(['slug' => Yii::$app->request->getQueryParam('slug')]);
            $searchModel->category_id = $category->id;

        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('archive', [
            'title' => isset($category) ? $category->title : Module::t('blog', "Գրառումներ"),
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

        $this->getModule()->openGraph->set([
            'title' => $post->title,
            'description' => $post->brief,
            'image' => $post->getThumbFileUrl('banner', 'facebook'),
            'imageWidth' => "600",
            'imageHeight' => "315",
        ]);


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
            'banners' => $this->getModule()->banners,

        ]);
    }
}
