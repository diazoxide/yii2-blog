<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * @property Module module
 */
class DefaultController extends BaseAdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'regenerate-thumbnails' => ['POST'],
                ],
            ],

            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'thumbnails', 'regenerate-thumbnails'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['thumbnails', 'regenerate-thumbnails'],
                        'allow' => true,
                        'roles' => ['BLOG_REGENERATE_THUMBNAILS']
                    ],
                ]
            ]

        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionThumbnails()
    {
        $post_count = BlogPost::find()->count();
        return $this->render('thumbnails', [
            'count' => $post_count
        ]);
    }

    public function actionRegenerateThumbnails($offset, $limit)
    {

        $posts = BlogPost::find()->orderBy(['id' => SORT_DESC])->limit($limit)->offset($offset)->all();
        foreach ($posts as $key => $post) {
            $post->createThumbs();
        }

        echo Module::t(null, 'Thumbnails Generation Done: ' . $offset . ' - ' . ($limit + $offset)) . PHP_EOL;

    }

    public function actionTest(){
        echo BlogCategory::find()->where(['type_id'=>1])->findByData('wordpress_origin_id',140)->one()->title;
//        echo (new BlogCategory())->findByData('wordpress_origin_id',140)->andWhere(['type_id' =>1])->one()->title;
    }
}
