<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogCategorySearch;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\traits\IActiveStatus;
use paulzi\adjacencyList\AdjacencyListBehavior;
use paulzi\adjacencyList\AdjacencyListQueryTrait;
use Throwable;
use Yii;
use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlogCategoryController implements the CRUD actions for BlogCategory model.
 */
class PostTypeController extends Controller
{
    use AdjacencyListQueryTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'delete', 'create', 'update', 'view'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_POST_TYPES']
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_POST_TYPE']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['BLOG_DELETE_POST_TYPE']
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['BLOG_CREATE_POST_TYPE']
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['BLOG_UPDATE_POST_TYPE']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BlogCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = BlogPostType::find();

        $dataprovider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('index', [
            'dataprovider' => $dataprovider
        ]);
    }


    /**
     * @param integer $id
     * @return BlogPostType
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogPostType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new post type model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BlogPostType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing post type model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing post type model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->locked) {
            throw new NotSupportedException('You can not delete locked post types.');
        } else {
            $model->delete();
            return $this->redirect(['index']);
        }
    }

}
