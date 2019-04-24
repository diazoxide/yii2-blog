<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostBook;
use diazoxide\blog\models\BlogPostBookChapter;
use diazoxide\blog\models\BlogPostSearch;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * BlogPostController implements the CRUD actions for BlogPost model.
 */
class BlogPostController extends BaseAdminController
{

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
                'only' => ['index', 'delete', 'create', 'update', 'view', 'create-book', 'update-book', 'delete-book', 'create-book-chapter', 'update-book-chapter', 'delete-book-chapter'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_POSTS']
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['BLOG_VIEW_POST']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['BLOG_DELETE_POST']
                    ],

                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['BLOG_CREATE_POST']
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_UPDATE_OWN_POST', ['model' => $this->findModel(Yii::$app->request->getQueryParam('id'))])
                                || Yii::$app->user->can('BLOG_UPDATE_POST');
                        },

                    ],

                    [
                        'actions' => ['delete-book'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_DELETE_OWN_POST_BOOK', ['model' => $this->findBookModel(Yii::$app->request->getQueryParam('id'))->post])
                                || Yii::$app->user->can('BLOG_DELETE_POST_BOOK');
                        },
                    ],
                    [
                        'actions' => ['create-book'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_CREATE_OWN_POST_BOOK', ['model' => $this->findModel(Yii::$app->request->getQueryParam('post_id'))])
                                || Yii::$app->user->can('BLOG_CREATE_POST_BOOK');
                        },
                    ],
                    [
                        'actions' => ['update-book'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_UPDATE_OWN_POST_BOOK', ['model' => $this->findBookModel(Yii::$app->request->getQueryParam('id'))->post])
                                || Yii::$app->user->can('BLOG_UPDATE_POST_BOOK');
                        },

                    ],
                    [
                        'actions' => ['delete-book-chapter'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_DELETE_OWN_POST_BOOK_CHAPTER', ['model' => $this->findBookChapterModel(Yii::$app->request->getQueryParam('id'))->book->post])
                                || Yii::$app->user->can('BLOG_DELETE_POST_BOOK_CHAPTER');
                        },
                    ],
                    [
                        'actions' => ['create-book-chapter'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_CREATE_OWN_POST_BOOK_CHAPTER', ['model' => $this->findBookModel(Yii::$app->request->getQueryParam('book_id'))->post])
                                || Yii::$app->user->can('BLOG_CREATE_POST_BOOK_CHAPTER');
                        },
                    ],
                    [
                        'actions' => ['update-book-chapter'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('BLOG_UPDATE_OWN_POST_BOOK_CHAPTER', ['model' => $this->findBookChapterModel(Yii::$app->request->getQueryParam('id'))->book->post])
                                || Yii::$app->user->can('BLOG_UPDATE_POST_BOOK_CHAPTER');
                        },

                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all BlogPost models.
     * @param string $type
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($type = 'article')
    {
        $type_model = BlogPostType::findOne(['name' => $type]);

        if (!$type_model) {
            throw new NotFoundHttpException('The requested post type does not exist.');
        }

        $searchModel = new BlogPostSearch();
        $searchModel->scenario = BlogPostSearch::SCENARIO_ADMIN;
        $searchModel->type_id = $type_model->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $arrayCategory = BlogPost::getArrayCategory();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arrayCategory' => $arrayCategory,
            'breadcrumbs' => $this->module->breadcrumbs,
            'type' => $type_model
        ]);
    }

    /**
     * Displays a single BlogPost model.
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
     * Finds the BlogPost model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogPost the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogPost::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return BlogPostBook|null
     * @throws NotFoundHttpException
     */
    protected function findBookModel($id)
    {
        if (($model = BlogPostBook::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * @param $id
     * @return BlogPostBookChapter|null
     * @throws NotFoundHttpException
     */
    protected function findBookChapterModel($id)
    {
        if (($model = BlogPostBookChapter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new BlogPost model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($type)
    {
        $model = new BlogPost();

        $type_model = BlogPostType::findOne(['name' => $type]);

        if (!$type_model) {
            throw new NotFoundHttpException('The requested post type does not exist.');
        }

        $model->type_id = $type_model->id;

        if ($model->load(Yii::$app->request->post())) {

            $model->published_at = Yii::$app->formatter->asTimestamp($model->published);

            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'type' => $type_model

        ]);

    }

    /**
     * Updates an existing BlogPost model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $bookDataProvider = new ActiveDataProvider([
            'query' => $model->getBooks(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'book_page',
                'pageSizeParam' => 'book_page_size',
            ],
            'sort' => [
                'sortParam' => 'book_sort'
            ]
        ]);

        if ($model->load(Yii::$app->request->post())) {
            $model->published_at = Yii::$app->formatter->asTimestamp($model->published);
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'bookDataProvider' => $bookDataProvider,
        ]);
    }

    /**
     * Deletes an existing BlogPost model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == IActiveStatus::STATUS_ARCHIVE) {
            $model->delete();
        } else {
            $model->status = IActiveStatus::STATUS_ARCHIVE;
            $model->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreateBook($post_id)
    {
        $model = new BlogPostBook();

        $model->post_id = $post_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update-book', 'id' => $model->id]);
        }

        return $this->render('createBook', [
            'model' => $model,
        ]);

    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateBook($id)
    {
        $model = $this->findBookModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update-book', 'id' => $model->id]);
        }

        return $this->render('updateBook', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteBook($id)
    {
        $model = $this->findBookModel($id);
        if ($model->status == IActiveStatus::STATUS_ARCHIVE) {
            $model->delete();
        } else {
            $model->status = IActiveStatus::STATUS_ARCHIVE;
            $model->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateBookChapter($id)
    {
        $model = $this->findBookChapterModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update-book-chapter', 'id' => $model->id]);
        }

        return $this->render('updateBookChapter', [
            'model' => $model,
        ]);
    }

    public function actionCreateBookChapter($book_id)
    {
        $model = new BlogPostBookChapter();

        $model->book_id = $book_id;

        if (Yii::$app->request->getQueryParam('parent_id')) {
            $model->parent_id = Yii::$app->request->getQueryParam('parent_id');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update-book', 'id' => $model->book->id]);
        }

        return $this->render('createBookChapter', [
            'model' => $model,
        ]);

    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteBookChapter($id)
    {
        $model = $this->findBookChapterModel($id);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }



}
