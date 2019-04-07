<?php

namespace diazoxide\blog\traits;


use diazoxide\blog\Module;
use Yii;

trait ConfigTrait
{
    public $formSchema=[
        /*
    public $backendViewPath = '@vendor/diazoxide/yii2-blog/views/backend';

    public $frontendViewPath = '@vendor/diazoxide/yii2-blog/views/frontend';

    public $frontendViewsMap = [];

    public $frontendLayoutMap = [];

    public $frontendTitleMap = [];

    protected $_frontendViewsMap = [
        'blog/default/index' => 'index',
        'blog/default/view' => 'view',
        'blog/default/archive' => 'archive',
        'blog/default/book' => 'viewBook',
        'blog/default/chapter' => 'viewChapter',
        'blog/default/chapter-search' => 'searchBookChapter',
    ];

    public $urlManager = 'urlManager';

    public $imgFilePath = '@frontend/web/img/blog';

    public $imgFileUrl = '/img/blog';

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

    public $blogViewLayout = null;

    public $schemaOrg = [];

    public $addthisId = null;

    public $enableShareButtons = false;

    public $redactorModule = 'redactorBlog';

    public $userModel;// = \common\models\User::class;

    public $userPK = 'id';

    public $userName = 'username';

    public $blogTheme;

    protected $_isBackend;

    public $homeTitle = 'Blog';

    public $banners = [];

    public $htmlClass = "diazoxide_blog";
     */
    ];

    public static function getConfigFormSchema(){

        return [
            'controllerNamespace'=>[
                'title'=>Module::t('', 'Controller Namespace'),
                'type'=>'string',
            ],
             'backendViewPath'=>[
                'title'=>Module::t('', 'Backend View Path'),
                'type'=>'string',
             ],

        ];
    }

}
