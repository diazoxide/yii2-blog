<?php
/**
 * Project: yii2-blog for internal using
 * Author: akiraz2
 * Copyright (c) 2018.
 */

namespace app\modules\blog\controllers\frontend;

use app\modules\blog\models\BlogCategory;
use app\modules\blog\models\BlogComment;
use app\modules\blog\models\BlogCommentSearch;
use app\modules\blog\models\BlogPost;
use app\modules\blog\models\BlogPostSearch;
use app\modules\blog\Module;
use app\modules\blog\traits\IActiveStatus;
use app\modules\blog\traits\ModuleTrait;
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
        $categories = BlogCategory::find()->where(['status' => IActiveStatus::STATUS_ACTIVE, 'is_nav' => BlogCategory::IS_NAV_YES])
            ->orderBy(['sort_order' => SORT_ASC])->all();

        $cat_items = ArrayHelper::toArray($categories, [
            'app\modules\blog\models\BlogCategory' => [
                'label' => 'title',
                'url' => function ($cat) {
                    return ['default/index', 'category_id' => $cat->id, 'slug' => $cat->slug];
                },
            ],
        ]);

        $searchModel = new BlogPostSearch();
        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('home', [
            'cat_items' => $cat_items,
            'post_list' => $dataProvider
        ]);

    }


    public function actionCategory($slug)
    {
        $searchModel = new BlogPostSearch();
        $searchModel->scenario = BlogPostSearch::SCENARIO_USER;

        $category_id = BlogCategory::findOne(['slug'=>$slug])->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['category_id'=>$category_id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($slug)
    {
        $this->layout = Yii::$app->controller->module->blogViewLayout;
        $post = BlogPost::find()->where(['status' => IActiveStatus::STATUS_ACTIVE, 'slug' => $slug])->one();
        if ($post === null) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

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
