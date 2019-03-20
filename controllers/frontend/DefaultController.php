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
use diazoxide\blog\models\BlogPostBook;
use diazoxide\blog\models\BlogPostBookChapter;
use diazoxide\blog\models\BlogPostBookChapterSearch;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\ModuleTrait;
use diazoxide\blog\traits\StatusTrait;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * @property Module module
 */
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
        $featuredCategories = BlogCategory::find()->where(['is_featured' => true, 'status' => IActiveStatus::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_DESC]);

        return $this->render($this->module->getView(), [
            'title' => $this->getModule()->homeTitle,
            'banners' => $this->getModule()->banners,
            'featuredCategories' => $featuredCategories,
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

        return $this->render($this->module->getView(), [
            'title' => isset($category) ? $category->title : Module::t("Գրառումներ"),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTest()
    {
        echo Yii::$app->controller->route;
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {

        if (isset($this->module->frontendLayoutMap[$this->route])) {
            $this->layout = $this->module->frontendLayoutMap[$this->route];
        }

        return parent::beforeAction($action);
    }


    /**
     * @param $slug
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        $id = Yii::$app->request->get('id');
        $slug = Yii::$app->request->get('slug');

        $post = BlogPost::find();
        if ($slug) {
            $post = $post->where(['status' => IActiveStatus::STATUS_ACTIVE, 'slug' => $slug])->one();
        } elseif ($id) {
            $post = $post->where(['status' => IActiveStatus::STATUS_ACTIVE, 'id' => $id])->one();
        }

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
            Yii::$app->session->setFlash('success', Module::t('A comment has been added and is awaiting validation'));
            return $this->redirect(['view', 'id' => $post->id, '#' => $comment->id]);
        }

        return $this->render($this->module->getView(), [
            'post' => $post,
            'dataProvider' => $dataProvider,
            'comment' => $comment,
            'banners' => $this->getModule()->banners,
            'showClicks' => $this->getModule()->showClicksInPost,
            'showDate' => $this->getModule()->showDateInPost,
            'dateType' => $this->getModule()->dateTypeInPost,
        ]);
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBook($slug)
    {
        $book = BlogPostBook::findOne(['slug' => $slug]);
        if ($book->status != IActiveStatus::STATUS_ACTIVE) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render($this->module->getView(), [
            'book' => $book
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionChapter($id)
    {

        $chapter = BlogPostBookChapter::findOne($id);
        if (!$chapter) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render($this->module->getView(), [
            'chapter' => $chapter
        ]);
    }

    public function actionChapterSearch($book_id)
    {
        $searchModel = new BlogPostBookChapterSearch();
        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;
        $searchModel->book_id = $book_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($this->module->getView(), [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }
}
