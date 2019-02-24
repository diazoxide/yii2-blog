<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog;

use Yii;
use yii\i18n\PhpMessageSource;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'diazoxide\blog\controllers\frontend';

    public $urlManager = 'urlManager';

    public $imgFilePath = '@frontend/web/img/blog';

    public $imgFileUrl = '/img/blog';

    public $adminAccessControl = null;

    public $blogPostPageCount = 10;

    public $blogCommentPageCount = 20;

    public $enableComments = false;

    public $enableLocalComments = false;

    public $enableFacebookComments = true;

    public $showBannerInPost = false;

    public $blogViewLayout = null;

    public $schemaOrg = [];

    public $addthisId = null;

    public $enableShareButtons = false;

    /** @var string Name of Component for editing posts */
    public $redactorModule = 'redactorBlog';

    /** @var linked user (for example, 'common\models\User::class' */
    public $userModel;// = \common\models\User::class;

    /** @var string Primary Key for user table, by default 'id' */
    public $userPK = 'id';

    /** @var string username uses in view (may be field `username` or `email` or `login`) */
    public $userName = 'username';

    public $blogTheme;

    protected $_isBackend;


    public $permissions = [
        ['BLOG_BULK_COMMENTS','Bulk blog comments'],
        ['BLOG_CONFIRM_ALL_COMMENTS','Confirm all blog comments'],
        ['BLOG_CREATE_CATEGORY','Create blog category'],
        ['BLOG_CREATE_COMMENT','Create blog comment'],
        ['BLOG_CREATE_POST','Create blog post'],
        ['BLOG_CREATE_TAG','Create blog tag'],
        ['BLOG_DELETE_ALL_COMMENTS','Delete all blog comments'],
        ['BLOG_DELETE_CATEGORY','Delete blog cateogry'],
        ['BLOG_DELETE_COMMENT','Delete blog comment'],
        ['BLOG_DELETE_POST','Delete blog post'],
        ['BLOG_DELETE_TAG','Delete blog tag'],
        ['BLOG_UPDATE_CATEGORY','Update blog cateogry'],
        ['BLOG_UPDATE_COMMENT','Update comment'],
        ['BLOG_UPDATE_OWN_POST','Update own blog post',['diazoxide/rbac/AuthorRule']],
        ['BLOG_UPDATE_POST','Update blog post'],
        ['BLOG_UPDATE_TAG','Update blog tag'],
        ['BLOG_VIEW_CATEGORIES','View blog categories'],
        ['BLOG_VIEW_CATEGORY','View blog category'],
        ['BLOG_VIEW_COMMENT','View blog comment'],
        ['BLOG_VIEW_COMMENTS','View blog comments'],
        ['BLOG_VIEW_POST','View blog post'],
        ['BLOG_VIEW_POSTS','View blog posts'],
        ['BLOG_VIEW_TAG','View blog tag'],
        ['BLOG_VIEW_TAGS','View blog tags'],
    ];

    public $roles = [
        ['BLOG_ADMIN','Blog Administrator'],
        ['BLOG_MANAGER','Blog Administrator'],
        ['BLOG_EDITOR','Blog Editor'],
    ];
    /**
     *
     */
    public function init()
    {
        parent::init();
        if ($this->getIsBackend() === true) {
            $this->setViewPath('@app/modules/blog/views/backend');
        } else {
            $this->setViewPath('@app/modules/blog/views/frontend');
            $this->setLayoutPath('@app/modules/blog/views/frontend/layouts');
        }
        $this->registerRedactorModule();
        $this->registerTranslations();

    }

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

    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/blog'] = [
            'class' => PhpMessageSource::class,
            'basePath' => '@app/modules/blog/messages',
            'forceTranslation' => true,
            'fileMap' => [
                'modules/blog' => 'blog.php',
            ]
        ];

    }


    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
     *
     * The translation will be conducted according to the message category and the target language will be used.
     *
     * You can add parameters to a translation message that will be substituted with the corresponding value after
     * translation. The format for this is to use curly brackets around the parameter name as you can see in the following example:
     *
     * ```php
     * $username = 'Alexander';
     * echo \Yii::t('app', 'Hello, {username}!', ['username' => $username]);
     * ```
     *
     * Further formatting of message parameters is supported using the [PHP intl extensions](http://www.php.net/manual/en/intro.intl.php)
     * message formatter. See [[\yii\i18n\I18N::translate()]] for more details.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     *
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/' . $category, $message, $params, $language);
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

    public static function getBlogNavigation()
    {
        return [
            ['label' => 'Posts', 'url' => ['/blog/blog-post'], 'visible' => Yii::$app->user->can("BLOG_VIEW_POSTS")],
            ['label' => 'Categories', 'url' => ['/blog/blog-category', 'visible' => Yii::$app->user->can("BLOG_VIEW_CATEGORIES")]],
            ['label' => 'Comments', 'url' => ['/blog/blog-comment'], 'visible' => Yii::$app->user->can("BLOG_VIEW_COMMENTS")],
            ['label' => 'Tags', 'url' => ['/blog/blog-tag'], 'visible' => Yii::$app->user->can("BLOG_VIEW_TAGS")],
        ];
    }
}
