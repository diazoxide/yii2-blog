<?php

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\importer\Wordpress;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use diazoxide\blog\traits\StatusTrait;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yiidreamteam\upload\exceptions\FileUploadException;

/**
 * @property Module module
 */
class ImporterController extends \yii\web\Controller
{

    public const ACTION_VALIDATE = 'validate';
    public const ACTION_SUCCESS = 'success';
    public const ACTION_IMPORT_CATEGORIES = 'import-categories';
    public const ACTION_IMPORT_POSTS = 'import-posts';
    public const ACTION_IMPORT_TAGS = 'import-tags';

    public function downloadImage($url, $id)
    {
        $url = str_replace("https://", "http://", $url);

        $re = '/^.*\.(jpg|jpeg|png|gif)$/i';

        preg_match($re, $url, $matches, PREG_OFFSET_CAPTURE, 0);

        if (!isset($matches[1][0])) {
            return null;
        }

        $extension = $matches[1][0];

        $name = $id . '.' . $extension;

        $path = \Yii::getAlias($this->module->imgFilePath) . '/blogPost/' . $name;

        if (!file_exists($path)) {
            try {
                file_put_contents($path, fopen($url, 'r'));
            } catch (\Exception $exception) {

            }
        }
        return $name;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionWordpress()
    {
        $wordpress = new Wordpress();
        $posts = [];
        $action = Yii::$app->request->get('action') ? Yii::$app->request->get('action') : $this::ACTION_VALIDATE;

        if ($wordpress->load(Yii::$app->request->get())) {

            if ($wordpress->validate()) {
                $args = Yii::$app->request->get();

                if ($action == $this::ACTION_VALIDATE) {

                    Yii::$app->session->setFlash('success', Module::t('', "System ready to start importing."));

                } elseif ($action == $this::ACTION_IMPORT_CATEGORIES) {

                    $categories = $wordpress->getCategories();
                    if (empty($categories)) {
                        $args[$wordpress->formName()]['page'] = 1;
                        $args['action'] = $this::ACTION_IMPORT_POSTS;
                        array_unshift($args, 'wordpress');
                        return $this->redirect($args);
                    }
                    foreach ($categories as $key => $category) {
                        if ($category['id'] == 1) {
                            continue;
                        }
                        $model = BlogCategory::findOne($category['id']);
                        $model = $model ? $model : new BlogCategory();
                        $model->id = $category['id'];
                        $model->title = $category['name'];
                        $model->slug = urldecode($category['slug']);
                        $parent_id = $category['parent'] == 0 ? 1 : $category['parent'];
                        $parent = BlogCategory::findOne($parent_id);
                        if (!$parent) {
                            $parent = new BlogCategory();
                            $parent->id = $parent_id;
                            $parent->title = 'Waiting...';
                            $parent->slug = 'waiting_' . $key;
                            $parent->prependTo(BlogCategory::findOne(1))->save();
                        }
                        $model->prependTo($parent)->save();
                    }

                    $args[$wordpress->formName()]['page']++;
                    array_unshift($args, 'wordpress');
                    return $this->redirect($args);

                } elseif ($action == $this::ACTION_IMPORT_POSTS) {
                    $posts = $wordpress->getPosts();

                    if (empty($posts)) {
                        $args['action'] = $this::ACTION_SUCCESS;
                        array_unshift($args, 'wordpress');
                        return $this->redirect($args);
                    }

                    foreach ($posts as $post) {
                        $model = BlogPost::findOne($post['id']);
                        $model = $model ? $model : new BlogPost();
                        $model->id = $post['id'];

                        $model->content = $post['content']['rendered'];
                        $model->created_at = Yii::$app->formatter->asTimestamp($post['date']);
                        $model->published_at = Yii::$app->formatter->asTimestamp($post['date']);

                        if (urldecode($post['slug'] == '')) {
                            $model->slug = urldecode($post['slug']);
                        }

                        $model->title = Html::encode($post['title']['rendered']);
                        $model->category_id = $post['categories'][0];
                        $model->category_ids = $post['categories'];
                        $model->status = IActiveStatus::STATUS_ACTIVE;

                        if (isset($post['_embedded']['wp:featuredmedia'])) {
                            foreach ($post['_embedded']['wp:featuredmedia'] as $media) {
                                if (isset($media['id']) && $media['id'] == $post['featured_media']) {
                                    $model->banner = $this->downloadImage($media['source_url'], $model->id);
                                }
                            }
                            if ($model->validate() && $model->save()) {
                                $model->createThumbs();
                            }
                        }
                    }
                    $args[$wordpress->formName()]['page']++;
                    array_unshift($args, 'wordpress');
                    $redirect_url = Url::to($args);
                    $this->view->registerJs('setTimeout(function(){window.location.href="' . $redirect_url . '";},2000)');
//                    return $this->redirect($args);
                }
            } else {
                $action = $this::ACTION_VALIDATE;
            }
        }
        return $this->render('wordpress', [
            'wordpress' => $wordpress,
            'posts' => $posts,
            'action' => $action
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
