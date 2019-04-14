<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogCategorySearch;
use diazoxide\blog\traits\IActiveStatus;
use paulzi\adjacencyList\AdjacencyListBehavior;
use paulzi\adjacencyList\AdjacencyListQueryTrait;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlogCategoryController implements the CRUD actions for BlogCategory model.
 */
class BlogCategoryController extends Controller
{
    use AdjacencyListQueryTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete', 'create', 'update', 'view'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_CATEGORIES']
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_CATEGORY']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['BLOG_DELETE_CATEGORY']
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['BLOG_CREATE_CATEGORY']
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['BLOG_UPDATE_CATEGORY']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BlogCategory models.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $model = $this->findModel(1);
        $dataProvider = $model->children;
        return $this->render('index', [
            'breadcrumbs' => $this->module->breadcrumbs,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogCategory model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the BlogCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new BlogCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $parent_id = Yii::$app->request->get('parent_id');

        $parent_id = $parent_id ? $parent_id : 1;

        $model = new BlogCategory();

        $parent = $this->findModel($parent_id);

        $model->parent_id = $parent_id;

        if ($model->load(Yii::$app->request->post())) {

            $model->prependTo($parent)->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BlogCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        $model->status = IActiveStatus::STATUS_ARCHIVE;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @param bool $top
     * @throws NotFoundHttpException
     */
    public function actionReorder($id, $action)
    {
        $model = $this->findModel($id);
        if ($action == 'up') {
            $neighbor = $model->getPrev()->one();
            if ($neighbor) {
                $model->moveBefore($neighbor)->save();
            }
        } elseif ($action == 'down') {
            $neighbor = $model->getNext()->one();
            if ($neighbor) {
                $model->moveAfter($neighbor)->save();
            }
        } elseif ($action == 'first') {
            $model->moveFirst()->save();
        } elseif ($action == 'last') {
            $model->moveLast()->save();
        }
        $this->redirect(Yii::$app->request->referrer);
    }
}
