<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog;

use diazoxide\blog\models\BlogComment;
use diazoxide\blog\models\BlogPostType;
use diazoxide\blog\models\BlogTag;
use diazoxide\blog\models\BlogWidgetType;
use Yii;
use yii\base\ViewNotFoundException;
use yii\db\ActiveRecord;
use diazoxide\blog\assets\AdminAsset;
use diazoxide\blog\assets\AppAsset;
use diazoxide\blog\components\JsonLDHelper;
use diazoxide\blog\components\OpenGraph;
use diazoxide\blog\models\BlogCategory;
use diazoxide\blog\models\BlogPost;
use diazoxide\blog\traits\IActiveStatus;
use himiklab\sitemap\behaviors\SitemapBehavior;

/**
 * @property array breadcrumbs
 * @property JsonLDHelper JsonLD
 * @property string homeUrl
 */
class Module extends \yii\base\Module
{

    const EVENT_BEFORE_POST_CONTENT_VIEW = 1;
    const EVENT_AFTER_POST_CONTENT_VIEW = 2;
    const EVENT_BEFORE_POST_BOOK_VIEW = 3;
    const EVENT_AFTER_POST_BOOK_VIEW = 4;

    public $controllerNamespace = 'diazoxide\blog\controllers\frontend';

    public $backendViewPath = '@vendor/diazoxide/yii2-blog/views/backend';

    public $frontendViewPath = '@vendor/diazoxide/yii2-blog/views/frontend';

    public $frontendViewsMap = [];

    public $frontendLayoutMap = [];

    public $frontendTitleMap = [];

    public $urlManager = 'urlManager';

    public $imgFilePath = '@frontend/web/img/blog';

    public $imgFileUrl = '/img/blog';

    public $social = [
        // the global settings for the disqus widget
        'disqus' => [
            'settings' => ['shortname' => 'DISQUS_SHORTNAME'] // default settings
        ],

        // the global settings for the facebook plugins widget
        'facebook' => [
            'app_id' => '440598006382886',
            'app_secret' => '528d7aa278399d3d5719a64b2f137e5e',
        ],

        // the global settings for the google plugins widget
        'google' => [
            'clientId' => 'GOOGLE_API_CLIENT_ID',
            'pageId' => 'GOOGLE_PLUS_PAGE_ID',
            'profileId' => 'GOOGLE_PLUS_PROFILE_ID',
        ],

        // the global settings for the google analytic plugin widget
        'google_analytics' => [
            'id' => 'UA-114602186-1',
            'domain' => 'new.irakanum.am',
        ],

        // the global settings for the twitter plugins widget
        'twitter' => [
            'screenName' => 'TWITTER_SCREEN_NAME'
        ],

        'addthis' => [
            'pubid' => 'ADDTHIS_ID'
        ]
    ];

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
    public $homeDescription = 'My New Blog';
    public $homeKeywords = 'Blog keywords';

    public $htmlClass = "diazoxide_blog";


    protected $_isBackend;

    /*
     * Default view files for routes
     * */
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
     * Getting the view file for current route
     * Using this method in controller actions
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


    /*
     * Init module.
     * Registering sub modules
     * Sitemap module "\himiklab\sitemap\Sitemap"
     * Automatic generation sitemap.xml files for SEO integration
     *
     * Registering module components
     * JsonLD component JsonLDHelper
     * Automatic JsonLD generation for each post and categories SEO Optimisation
     *
     * */
    public function init()
    {
        parent::init();

        $this->modules = [
            'sitemap' => [
                'class' => \himiklab\sitemap\Sitemap::class,
                'models' => [
                    [
                        'class' => BlogPost::class,
                        'behaviors' => [
                            'sitemap' => [
                                'class' => SitemapBehavior::class,
                                'scope' => function ($model) {
                                    /** @var \yii\db\ActiveQuery $model */
                                    $model->select(['published_at', 'slug']);
                                    $model->andWhere(['status' => IActiveStatus::STATUS_ACTIVE]);
                                },
                                'dataClosure' => /**
                                 * @param BlogPost $model
                                 * @return array
                                 */
                                    function ($model) {
                                        return [
                                            'loc' => $model->url,
                                            'lastmod' => $model->published_at,
                                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                                            'priority' => 0.8,
                                        ];
                                    }
                            ],
                        ],
                    ],
                    [
                        'class' => BlogCategory::class,
                        'behaviors' => [
                            'sitemap' => [
                                'class' => SitemapBehavior::class,
                                'scope' => function ($model) {
                                    /** @var \yii\db\ActiveQuery $model */
                                    $model->select(['created_at', 'slug']);
                                    $model->andWhere(['status' => IActiveStatus::STATUS_ACTIVE]);
                                },
                                'dataClosure' => /**
                                 * @param BlogPost $model
                                 * @return array
                                 */
                                    function ($model) {
                                        return [
                                            'loc' => $model->url,
                                            'lastmod' => $model->created_at,
                                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                                            'priority' => 1,
                                        ];
                                    }
                            ],
                        ],
                    ],
                ],
                'enableGzip' => true,
                'cacheExpire' => 1,
            ],
        ];

        $this->components = [
            'JsonLD' => [
                "class" => JsonLDHelper::class,
                "publisher" => (object)[
                    "@type" => "Organization",
                    "http://schema.org/name" => isset($this->schemaOrg['publisher']['name']) ? $this->schemaOrg['publisher']['name'] : "",
                    "http://schema.org/logo" => (object)[
                        "@type" => "http://schema.org/ImageObject",
                        "http://schema.org/url" => isset($this->schemaOrg['publisher']['logo']) ? $this->schemaOrg['publisher']['logo'] : "",
                    ],
                    "http://schema.org/url" => $this->homeUrl
                ]
            ]
        ];

        if ($this->getIsBackend() === true) {
            $this->setViewPath($this->backendViewPath);
            AdminAsset::register(Yii::$app->view);
        } else {
            AppAsset::register(Yii::$app->view);
            $this->setViewPath($this->frontendViewPath);
        }
    }

    /**
     * @param $category
     * @param $message
     * @param null $language
     * @param array $params
     * @return string
     */
    public static function t($category, $message, $language = null, $params = [])
    {
        return Yii::t('diazoxide/blog' . $category, $message, $params, $language);
    }


    /**
     * Check if module is used for backend application.
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
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getImgFullPathUrl()
    {
        return \Yii::$app->get($this->urlManager)->getHostInfo() . $this->imgFileUrl;
    }

    /**
     * Backend navigation items
     * You can easily integrate this function in your backend panel
     * @return array
     */
    public function getNavigation()
    {
        $post_types = BlogPostType::find()->all();
        $post_types_items = [];
        /** @var BlogPostType $type */
        foreach ($post_types as $type) {
            $post_types_items[] = [
                'label' => $type->title,
                'url' => ["/{$this->id}/blog-post", 'type' => $type->name,],
                'items' => [
                    [
                        'label' => Module::t('', 'Categories'),
                        'url' => ["/{$this->id}/blog-category", 'type' => $type->name],
                        'visible' => $type->has_category && Yii::$app->user->can("BLOG_VIEW_CATEGORIES")
                    ]
                ]
            ];
        }
        return [
            ['label' => Module::t('', 'Blog'),
                'items' => [
                    ['label' => Module::t('', 'Posts Types'),
                        'url' => ["/{$this->id}/post-type"],
                        'visible' => Yii::$app->user->can("BLOG_VIEW_POSTS"),
                        'items' => $post_types_items
                    ],
//                    ['label' => Module::t('', 'Categories') . ' (' . BlogCategory::find()->count() . ')', 'url' => ["/{$this->id}/blog-category"], 'visible' => Yii::$app->user->can("BLOG_VIEW_CATEGORIES")],
                    ['label' => Module::t('', 'Comments') . ' (' . BlogComment::find()->count() . ')', 'url' => ["/{$this->id}/blog-comment"], 'visible' => Yii::$app->user->can("BLOG_VIEW_COMMENTS")],
                    ['label' => Module::t('', 'Tags') . ' (' . BlogTag::find()->count() . ')', 'url' => ["/{$this->id}/blog-tag"], 'visible' => Yii::$app->user->can("BLOG_VIEW_TAGS")],
                    ['label' => Module::t('', 'Widget Types') . ' (' . BlogWidgetType::find()->count() . ')', 'url' => ["/{$this->id}/widget-type/index"], 'visible' => Yii::$app->user->can("BLOG_VIEW_WIDGET_TYPES")],
                    ['label' => Module::t('', 'Options'),
                        'items' => [
                            ['label' => 'Importer', 'url' => ["/{$this->id}/importer/index"]],
                            ['label' => 'Thumbnails', 'url' => ["/{$this->id}/default/thumbnails"], 'visible' => Yii::$app->user->can("BLOG_REGENERATE_THUMBNAILS")]
                        ]
                    ]
                ]
            ],
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
     * Get the categories url for breadcrumbs and e.t.c.
     * For backend and frontend different urls
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
     * Getting blog home url
     * For backend and frontend
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
     * Getting archive url
     * For backend and frontend
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
     * Getting the nested breadcrumbs element
     * For global breadcrumbs
     * This is Module root breadcrumb element
     * @return array
     */
    public function getBreadcrumbs()
    {
        $result = [];
        $result[] = ['label' => Module::t('', 'Blog'), 'url' => $this->homeUrl];
        return $result;
    }

    /**
     * Getting the nested breadcrumbs element
     * For global breadcrumbs
     * @return array|mixed
     */
    public function getCategoryBreadcrumbs()
    {
        $result = $this->breadcrumbs;
        $result[] = ['label' => Module::t('', 'Categories'), 'url' => $this->categoriesUrl];
        return $result;
    }


    /**
     * Helper method for converting date, time, dateTime
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
