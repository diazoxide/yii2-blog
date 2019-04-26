<?php

namespace diazoxide\blog\controllers\backend;

use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\models\importer\Wordpress;
use diazoxide\blog\Module;
use diazoxide\blog\traits\IActiveStatus;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;


/**
 * @property Module module
 */
class ImporterController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'wordpress'],
                'rules' => [
                    [
                        'actions' => ['index', 'wordpress'],
                        'allow' => true,
                        'roles' => ['BLOG_IMPORT_POSTS']
                    ],
                ],
            ],
        ];
    }


    public const ACTION_VALIDATE = 'validate';
    public const ACTION_SUCCESS = 'success';
    public const ACTION_IMPORT_CATEGORIES = 'import-categories';
    public const ACTION_IMPORT_POSTS = 'import-posts';
    public const ACTION_IMPORT_TAGS = 'import-tags';

    /**
     * @param $url
     * @param $id
     * @param $type_id
     * @return null|string
     * @throws \yii\base\Exception
     */
    public function downloadImage($url, $id, $type_id)
    {
        $url = str_replace("https://", "http://", $url);

        $re = '/^.*\.(jpg|jpeg|png|gif)$/i';

        preg_match($re, $url, $matches, PREG_OFFSET_CAPTURE, 0);

        if (!isset($matches[1][0])) {
            return null;
        }

        $extension = $matches[1][0];
        $name = $id . '.' . $extension;
        $dir_path = \Yii::getAlias($this->module->imgFilePath) . '/post/' . $type_id ;

        if (!is_dir($dir_path)) {
            FileHelper::createDirectory($dir_path, $mode = 0775, $recursive = true);
        }

        $path = $dir_path. '/' . $name;

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
     * @throws \yii\base\Exception
     */
    public function actionWordpress()
    {
        $wordpress = new Wordpress();
        $posts = [];

        $action = Yii::$app->request->get('action') ? Yii::$app->request->get('action') : $this::ACTION_VALIDATE;

        /*
         * Loading model from get parameters
         * */
        if ($wordpress->load(Yii::$app->request->get())) {

            if ($wordpress->validate()) {

                /*
                 * Getting url get params
                 * */
                $args = Yii::$app->request->get();

                /*
                 * Getting post type model
                 * */
                $post_type = BlogPostType::findOne($wordpress->post_type_id);

                /*
                 * Validation action
                 * */
                if ($action == $this::ACTION_VALIDATE) {

                    Yii::$app->session->setFlash('success', Module::t('', "System ready to start importing."));

                } elseif ($action == $this::ACTION_IMPORT_CATEGORIES) {

                    /*
                     * If Post type not supported categories
                     * Than redirect to post import action
                     * */
                    if (!$post_type->has_category) {
                        $args['action'] = $this::ACTION_IMPORT_POSTS;
                        array_unshift($args, 'wordpress');
                        return $this->redirect($args);
                    }

                    /*
                     * Getting categories from already initialised wordpress rest api object
                     * */
                    $categories = $wordpress->getCategories();

                    /*
                     * If current page of categories
                     * Is empty than
                     * Update hierarchy of categories
                     * Redirect already to post import action
                     * */
                    if (empty($categories)) {

                        /*
                         * Updating hierarchy
                         * */
                        foreach (BlogCategory::findAll(['type_id' => $wordpress->post_type_id]) as $category) {

                            $origin_parent_id = $category->getDataValue($wordpress->key . '_origin_parent_id', false);

                            if ($origin_parent_id) {
                                echo $origin_parent_id->value;
                                $parent = BlogCategory::find()->joinWith(['data data'])->where(['type_id' => $wordpress->post_type_id, 'data.name' => $wordpress->key . '_origin_id', 'data.value' => $origin_parent_id->value])->one();
                                if ($parent) {
                                    $category->prependTo($parent)->save();
                                }
                            }
                        }

                        /*
                         * Redirecting to import post action
                         * */
                        $args[$wordpress->formName()]['page'] = 1;
                        $args['action'] = $this::ACTION_IMPORT_POSTS;
                        array_unshift($args, 'wordpress');
                        return $this->redirect($args);
                    }

                    foreach ($categories as $key => $category) {

                        /*
                         * Skipping id=1 category
                         * Because first category is main root category
                         * And in wordpress first category is uncategoriezed
                         * */
                        if ($category['id'] == 1) {
                            continue;
                        }

                        /*
                         * Inserting new category to database
                         * Or update existing model
                         * */
                        $model = BlogCategory::find()->joinWith(['data data'])->where(['type_id' => $wordpress->post_type_id, 'data.name' => $wordpress->key . '_origin_id', 'data.value' => $category['id']])->one();
                        $model = $model ? $model : new BlogCategory();
                        $model->type_id = $wordpress->post_type_id;
                        $model->title = $category['name'];
                        $model->slug = urldecode($category['slug']);
                        $parent_id = $category['parent'] == 0 ? 1 : $category['parent'];
                        $model->setDataValue($wordpress->key . '_origin_id', $category['id']);
                        $model->setDataValue($wordpress->key . '_origin_parent_id', $parent_id);
                        $model->prependTo(BlogCategory::findOne(1))->save();
                    }

                    /*
                     * After complete the import action
                     * Change number of page and redirect to new page
                     * */
                    $args[$wordpress->formName()]['page']++;
                    array_unshift($args, 'wordpress');
                    return $this->redirect($args);

                } elseif ($action == $this::ACTION_IMPORT_POSTS) {

                    /*
                     * Getting post from already initialised wordpress rest api
                     * */
                    $posts = $wordpress->getPosts();

                    /*
                     * If current page of posts
                     * Is empty than redirect already to success action
                     * */
                    if (empty($posts)) {
                        $args['action'] = $this::ACTION_SUCCESS;
                        array_unshift($args, 'wordpress');
                        return $this->redirect($args);
                    }

                    foreach ($posts as $post) {

                        /*
                         * Creating new BlogPost object model
                         * */
                        $model = new BlogPost();

                        /*
                         * If overwrite enabled than for first finding old post
                         * If old post detected than overwriting old post
                         * But if old post not detected than setting new model id from origin
                         * */
                        if ($wordpress->overwrite) {
                            $old_model = (new BlogCategory())->findByData($wordpress->key . '_origin_id', $post['id'])->andWhere(['type_id' => $wordpress->post_type_id])->one();
                            if ($old_model) {
                                $model = $old_model;
                            }
                        }

                        /*
                         * Setting key attribute for next import
                         * If post already created update the post uses this property
                         * */
                        $model->setDataValue($wordpress->key . '_origin_id', $post['id']);

                        /*
                         * Setting post type_id property
                         * */
                        $model->type_id = $wordpress->post_type_id;

                        /*
                         * If localize content is enabled
                         * Than download all content images in server
                         * */
                        $model->content = $wordpress->localize_content ? $this->localizeContent($post['content']['rendered']) : $post['content']['rendered'];

                        $model->brief = urldecode(trim(html_entity_decode(strip_tags($post['excerpt']['rendered']))));
                        $model->created_at = Yii::$app->formatter->asTimestamp($post['date']);
                        $model->published_at = Yii::$app->formatter->asTimestamp($post['date']);

                        $slug = HtmlPurifier::process(urldecode($post['slug']));

                        /*
                         * If slug is not empty than setting slug
                         * But if empty slug generating automatically
                         * */
                        if ($slug != '') {
                            $model->slug = $slug;
                        }

                        $model->title = Html::encode($post['title']['rendered']);

                        $category = (new BlogCategory())->findByData($wordpress->key . '_origin_id', $post['categories'][0])->andWhere(['type_id' => $wordpress->post_type_id])->one();

                        if ($category) {
                            $model->category_id = $category->id;
                            Yii::info('Category exists ' . $category->id, self::class);
                        } else {
                            $model->category_id = 1;
                            Yii::warning('Category not found ' . $post['categories'][0], self::class);
                        }

//                        $model->category_ids = $post['categories'];
                        $model->status = IActiveStatus::STATUS_ACTIVE;

                        /*
                         * Validate and save model in DB
                         * */
                        if ($model->validate() && $model->save()) {

                            /*
                             * If post type has banner
                             * And isset featured media in original post
                             * Than for first downloading image from url
                             * Finally creating thumbs
                             * */
                            if ($post_type->has_banner && isset($post['_embedded']['wp:featuredmedia'])) {
                                foreach ($post['_embedded']['wp:featuredmedia'] as $media) {
                                    if (isset($media['id']) && $media['id'] == $post['featured_media']) {
                                        $model->banner = $this->downloadImage($media['source_url'], $model->id, $wordpress->post_type_id);
                                        $model->save();
                                    }
                                }
                                $model->createThumbs();
                            }

                            /*
                             * Logging complete message
                             * */
                            Yii::info('Import complete - ' . $post['id'] . ' - slug - ' . $slug, self::class);

                        } else {
                            /*
                             * Logging error message
                             * With model errors
                             * */
                            Yii::error('Import not complete - ' . $post['id'] . ' - slug - ' . $slug . print_r($model->errors, true), self::class);
                        }
                    }
                    $args[$wordpress->formName()]['page']++;
                    array_unshift($args, 'wordpress');
                    $redirect_url = Url::to($args);
                    $this->view->registerJs('setTimeout(function(){window.location.href="' . $redirect_url . '";},2000)');
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

    /**
     * @param $content
     * @return null|string|string[]
     */
    public function localizeContent($content)
    {
        if ($content) {
            $final = preg_replace_callback(
                '/\<img.*\ src="(.[^"]+)\"/i',
                function ($m) {
                    $url = $m[1];
                    $url = str_replace("https://", "http://", $url);
                    $extPattern = '/\.([A-Za-z0-9]+)$/i';
                    $name = md5($url);
                    preg_match($extPattern, $url, $matches, PREG_OFFSET_CAPTURE, 0);
                    $ext = isset($matches[0][0]) ? $matches[0][0] : false;
                    if (!$ext) {
                        return $url;
                    };

                    $path = Yii::getAlias($this->module->imgFilePath . '/' . $this->module->postContentImagesDirectory) . '/' . $name . $ext;
                    $localUrl = Yii::getAlias($this->module->imgFileUrl . '/' . $this->module->postContentImagesDirectory) . '/' . $name . $ext;

                    if (!file_exists($path)) {

                        try {

                            file_put_contents($path, fopen($url, 'r'));
                            $url = $localUrl;

                        } catch (\Exception $exception) {
                        }
                    } else {
                        $url = $localUrl;

                    }

                    return '<img src="' . $url . '"';
                },
                $content
            );
            return $final;
        } else return null;

    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
