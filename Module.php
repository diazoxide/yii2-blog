<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog;

use diazoxide\blog\assets\AdminAsset;
use diazoxide\blog\assets\AppAsset;
use diazoxide\blog\components\OpenGraph;
use diazoxide\blog\traits\ConfigTrait;
use Yii;
use yii\base\ViewNotFoundException;
use yii\db\ActiveRecord;
use yii\i18n\PhpMessageSource;

/**
 * @property array breadcrumbs
 */
class Module extends \yii\base\Module
{

    public $controllerNamespace = 'diazoxide\blog\controllers\frontend';

    public $backendViewPath = '@vendor/diazoxide/yii2-blog/views/backend';

    public $frontendViewPath = '@vendor/diazoxide/yii2-blog/views/frontend';

    public $frontendViewsMap = [];

    public $frontendLayoutMap = [];

    public $frontendTitleMap = [];

    public $urlManager = 'urlManager';

    public $imgFilePath = '@frontend/web/img/blog';

    public $imgFileUrl = '/img/blog';

    /* @var string
     * Final directory for post content uploads
     * When you use text redactor all uploaded images saving in
     * Directory: $imgFilePath/$_postContentImagesDirectory
     * */
    public $postContentImagesDirectory = "upload/post-content";


    public $adminAccessControl = null;

    public $blogPostPageCount = 10;

    public $blogCommentPageCount = 20;

    public $enableComments = false;

    public $enableBooks = true;

    public $enableLocalComments = false;

    public $enableFacebookComments = true;

    public $showBannerInPost = false;

    public $showClicksInPost = true;

    public $showClicksInArchive = false;

    public $showDateInPost = true;

    public $dateTypeInPost = 'dateTime';

    public $schemaOrg = [];

    public $addthisId = null;

    public $enableShareButtons = false;

    /** @var string Name of Component for editing posts */
    public $redactorModule = 'redactorBlog';

    /** @var ActiveRecord user (for example, 'common\models\User::class' */
    public $userModel;// = \common\models\User::class;

    /** @var string Primary Key for user table, by default 'id' */
    public $userPK = 'id';

    /** @var string username uses in view (may be field `username` or `email` or `login`) */
    public $userName = 'username';

    public $blogTheme;

    public $homeTitle = 'Blog';

    public $banners = [];

    public $htmlClass = "diazoxide_blog";


    protected $_isBackend;

    protected function _frontendViewsMap()
    {
        return [
            $this->id . '/default/index' => 'index',
            $this->id . '/default/view' => 'view',
            $this->id . '/default/archive' => 'archive',
            $this->id . '/default/book' => 'viewBook',
            $this->id . '/default/chapter' => 'viewChapter',
            $this->id . '/default/chapter-search' => 'searchBookChapter',
        ];
    }

    /**
     * @return mixed|string
     */
    public function getView()
    {
        $route = Yii::$app->controller->route;

        if ($this->getIsBackend() !== true) {

            if (isset($this->frontendViewsMap[$route])) {

                return $this->frontendViewsMap[$route];

            } elseif (isset($this->_frontendViewsMap()[$route])) {

                return $this->_frontendViewsMap()[$route];

            }
        }
        throw new ViewNotFoundException('The view file does not exist.');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->getIsBackend() === true) {
            $this->setViewPath($this->backendViewPath);
            AdminAsset::register(Yii::$app->view);
        } else {
            $this->setViewPath($this->frontendViewPath);
            AppAsset::register(Yii::$app->view);
        }
        $this->registerRedactorModule();
        $this->registerTranslations();

    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function registerRedactorModule()
    {
        $redactorModule = $this->redactorModule;
        if ($this->getIsBackend() && !Yii::$app->hasModule($redactorModule)) {
            Yii::$app->setModule($redactorModule, [
                'class' => 'yii\redactor\RedactorModule',
                'imageUploadRoute' => ['/blog/upload/image'],
                'uploadDir' => $this->imgFilePath . '/upload/',
                'uploadUrl' => $this->getImgFullPathUrl() . '/upload',
                'imageAllowExtensions' => ['jpg', 'png', 'gif', 'svg']
            ]);
        }
    }

    /**
     *
     */
    protected function registerTranslations()
    {
//        Yii::$app->i18n->translations['diazoxide/blog'] = [
//            'class' => PhpMessageSource::class,
//            'basePath' => '@vendor/diazoxide/yii2-blog/messages',
//            'forceTranslation' => true,
//            'fileMap' => [
//                'diazoxide/blog' => 'blog.php',
//            ]
//        ];

    }


    /**
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('diazoxide/blog', $message, $params, $language);
    }

    /**
     * Check if module is used for backend application.
     *
     * @return boolean true if it's used for backend application
     */
    public function getIsBackend()
    {
        if ($this->_isBackend === null) {
            $this->_isBackend = strpos($this->controllerNamespace, 'backend') === false ? false : true;
        }

        return $this->_isBackend;
    }

    /**
     * Need correct Full IMG URL for Backend
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getImgFullPathUrl()
    {
        return \Yii::$app->get($this->urlManager)->getHostInfo() . $this->imgFileUrl;
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        return [
            ['label' => Module::t('Blog'), 'items' => [
                ['label' => Module::t('Posts'), 'url' => ["/{$this->id}/blog-post"], 'visible' => Yii::$app->user->can("BLOG_VIEW_POSTS")],
                ['label' => Module::t('Categories'), 'url' => ["/{$this->id}/blog-category"], 'visible' => Yii::$app->user->can("BLOG_VIEW_CATEGORIES")],
                ['label' => Module::t('Comments'), 'url' => ["/{$this->id}/blog-comment"], 'visible' => Yii::$app->user->can("BLOG_VIEW_COMMENTS")],
                ['label' => Module::t('Tags'), 'url' => ["/{$this->id}/blog-tag"], 'visible' => Yii::$app->user->can("BLOG_VIEW_TAGS")],
                ['label' => Module::t('Widget Types'), 'url' => ["/{$this->id}/widget-type/index"], 'visible' => Yii::$app->user->can("BLOG_VIEW_WIDGET_TYPES")],
            ]],
        ];
    }

    /**
     * @return OpenGraph
     */
    public function getOpenGraph()
    {
        $opengraph = new OpenGraph();
        $opengraph->title = $this->homeTitle;
        return $opengraph;
    }

    /**
     * @return string
     */
    public function getCategoriesUrl()
    {
        if ($this->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl([$this->id . '/blog-category/index']);
        }
        return Yii::$app->getUrlManager()->createUrl([$this->id . '/default']);

    }

    /**
     * @return string
     */
    public function getHomeUrl()
    {

        if ($this->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl([$this->id . '/default/index']);
        }
        return Yii::$app->getUrlManager()->createUrl([$this->id . '/default/index']);

    }

    /**
     * @return string
     */
    public function getArchiveUrl()
    {

        if ($this->getIsBackend()) {
            return Yii::$app->getUrlManager()->createUrl([$this->id . '/default/index']);
        }
        return Yii::$app->getUrlManager()->createUrl([$this->id . '/default/archive']);

    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        $result = [];
        $result[] = ['label' => Module::t('Blog'), 'url' => $this->homeUrl];
        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getCategoryBreadcrumbs()
    {
        $result = $this->breadcrumbs;
        $result[] = ['label' => Module::t('Categories'), 'url' => $this->categoriesUrl];
        return $result;
    }


    /**
     * @param $dateStr
     * @param string $type
     * @param null $format
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function convertTime($dateStr, $type = 'date', $format = null)
    {
        if ($type === 'datetime') {
            $fmt = ($format == null) ? Yii::$app->formatter->datetimeFormat : $format;
        } elseif ($type === 'time') {
            $fmt = ($format == null) ? Yii::$app->formatter->timeFormat : $format;
        } else {
            $fmt = ($format == null) ? Yii::$app->formatter->dateFormat : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }
}
