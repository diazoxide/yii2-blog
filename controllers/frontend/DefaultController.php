<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\frontend;

use diazoxide\blog\components\ViewPatternHelper;
use diazoxide\blog\models\BlogPostType;
use Yii;
use yii\base\ViewNotFoundException;
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
class DefaultController extends Controller
{
    use ModuleTrait;

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index', 'view', 'archive'],
                'duration' => 999999,
                'variations' => [
                    Yii::$app->request->url
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => "SELECT updated_at FROM " . BlogPost::tableName() . " ORDER BY updated_at DESC LIMIT 1",
                ],
            ],
        ];
    }

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

    /**
     * @param string $type
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($type = 'article')
    {

        $type_model = BlogPostType::findOne(['name' => $type]);

        if (!$type_model) {
            throw new NotFoundHttpException('The requested post type does not exist.');
        }

        $featuredCategories = BlogCategory::find()->where(['type_id'=>$type_model->id,'is_featured' => true, 'status' => IActiveStatus::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_DESC]);

        $this->module->openGraph->set([
            'title' => $this->module->homeTitle,
            'description' => $this->module->homeDescription,
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $this->module->homeDescription
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $this->module->homeKeywords
        ]);

	    $pattern = $type_model->default_pattern;
	    $view = $this->module->getView();

	    /*
	     * Setting Title of page
	     * */
	    Yii::$app->view->title = $this->getModule()->homeTitle;

	    $params = [
		    'pattern'=>$pattern,
		    'type'=>$type_model,
		    'featuredCategories' => $featuredCategories,
	    ];


	    if($pattern){
		    return $this->renderContent(ViewPatternHelper::extract($pattern,$params));
	    } elseif($view){
		    return $this->render($view, $params);
	    } else{
		    throw new ViewNotFoundException('The requested view file or view pattern does not exist.');
	    }
    }

    /**
     * @param string $type
     * @param null $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionArchive($type = 'article', $slug = null)
    {
        $type_model = BlogPostType::findOne(['name' => $type]);

        if (!$type_model) {
            throw new NotFoundHttpException('The requested post type does not exist.');
        }

        $searchModel = new BlogPostSearch();
        $searchModel->type_id = $type_model->id;
        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;

        if ($slug) {
            $category = BlogCategory::findOne(['slug' => $slug]);
            $searchModel->category_id = $category->id;
        } else {
            $category = BlogCategory::findOne(1);
            $searchModel->category_id = 1;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->view->title = $type_model->title.' '.Module::t('','Archive');
        Yii::$app->view->params['breadcrumbs'] = $category->breadcrumbs;

        $pattern = $type_model->archive_pattern;
        $view = $this->module->getView();

        $params = [
            'title' => isset($category) ? $category->title : Module::t('', "Գրառումներ"),
            'category' => $category,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];

        if($pattern){
            return $this->renderContent(ViewPatternHelper::extract($pattern,$params));
        } elseif($view){
            return $this->render($view, $params);
        } else{
            throw new ViewNotFoundException('The requested view file or view pattern does not exist.');
        }

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


        /** @var BlogPost $post */
        if ($post->type->layout) {
            $this->layout = $post->type->layout;
        }

        $this->module->openGraph->set([
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

        /*
         * Setting page title and breadcrumbs
         * */
        Yii::$app->view->title = $post->title;
        Yii::$app->view->params['breadcrumbs'] = $post->breadcrumbs;
        Yii::$app->view->params['breadcrumbs'][] = $post->title;


        $post->updateCounters(['click' => 1]);

        $searchModel = new BlogCommentSearch();
        $searchModel->scenario = BlogComment::SCENARIO_USER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $post->id);

        $comment = new BlogComment();
        $comment->scenario = BlogComment::SCENARIO_USER;

        if ($comment->load(Yii::$app->request->post()) && $post->addComment($comment)) {
            Yii::$app->session->setFlash('success', Module::t('', 'A comment has been added and is awaiting validation'));
            return $this->redirect(['view', 'id' => $post->id, '#' => $comment->id]);
        }

        $params = [
	        'post' => $post,
	        'dataProvider' => $dataProvider,
	        'comment' => $comment,
	        'showClicks' => $this->getModule()->showClicksInPost,
	        'showDate' => $this->getModule()->showDateInPost,
	        'dateType' => $this->getModule()->dateTypeInPost,
        ];
	    $pattern = $post->type->single_pattern;
	    $view = $this->module->getView();

	    if($pattern){
		    return $this->renderContent(ViewPatternHelper::extract($pattern,$params));
	    } elseif($view){
		    return $this->render($view, $params);
	    } else{
		    throw new ViewNotFoundException('The requested view file or view pattern does not exist.');
	    }
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
