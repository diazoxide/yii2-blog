<?php

namespace diazoxide\blog;

use diazoxide\blog\models\BlogPostType;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {

        if ($app->db->getTableSchema(BlogPostType::tableName(), true) === null) {
            return;
        }

        $post_types = BlogPostType::find()->all();
        $rules = [
            /*
             * Category rage rule
             * Each category has custom page
             * F.e. https://yoursite.com/category/my_category_slug
             * */
            '<type>/category/<slug>' => '/blog/default/archive',

            /*
             * Archive page shows all posts
             * With pagination
             * */
            '/archive/<type>' => '/blog/default/archive',
            '/archive' => '/blog/default/archive',

            /*
             * Site map XML rule
             * F.e. http://yoursite.com/sitemap
             * */
            '/sitemap' => '/blog/sitemap',

            /*
             * Fixing old posts issue
             * */
//                '/archives/<id:\d+>' => '/blog/fixer/id',
//                [
//                    'pattern' => '<year:\d{4}>/<month:\d{2}>/<day:\d{2}>/<slug>',
//                    'route' => '/blog/fixer/slug',
//                    'suffix' => '/'
//                ],


        ];

        foreach ($post_types as $type) {
            if ($type->url_pattern != null) {
                $rules[] = [
                    'pattern' => $type->url_pattern,
                    'route' => '/blog/default/view',
                    'suffix' => '/'
                ];
                $rules[] = [
                    'pattern' => $type->url_pattern,
                    'route' => '/blog/default/view',
                ];

            }
        }

        /*
         * Adding module URL rules.
         * */
        $app->getUrlManager()->addRules(
            $rules
        );


        // Add module I18N category.
        if (!isset($app->i18n->translations['diazoxide/blog'])) {
            $app->i18n->translations['diazoxide/blog'] = [
                'class' => PhpMessageSource::class,
                'basePath' => __DIR__ . '/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'diazoxide/blog' => 'blog.php',
                ]
//                'class' => \yii\i18n\DbMessageSource::class,
//                'sourceMessageTable'=>'{{%source_message}}',
//                'messageTable'=>'{{%message}}',
//                'enableCaching' => true,
//                'cachingDuration' => 10,
//                'forceTranslation'=>true,

            ];
        }

        \Yii::setAlias('@diazoxide', \Yii::getAlias('@vendor') . '/diazoxide');
    }
}