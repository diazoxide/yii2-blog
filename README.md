# diazoxide/yii2-blog

Advanced Yii2 Blog module. Posts, Categories, Comments, Tags, With Slider Revolution, SEO tags, Social Tags

Flexible blog module like wordpress, you can use this module as CMS, or can use it as simple blog system. 
Easy to use and user friendly administrator dashboard. 

Responsive Bootstrap 3 user interface. (BS4 coming soon)
Mobile friendly, web browser optimised front end.

Fully configurable frontend design, without codding.


# Features

- Blog Posts
    - Title
    - Slug
    - Multiple Categories for each post
    - Brief
    - Content
            - TinyMCE/wysiwyg editor
    - Books
            - Chapters and sub Chapters
            - Nested structure
            - Nested breadcrumbs
            - BBCode support
            - Custom BBCode styling
- Blog Categories
    - Nested hierarchy structure
    - UI sorting
- Blog Tags
- Blog Comments
    - Local comments
    - Facebook comments
- Custom Widgets
    - Dynamic widgets
    - Custom backend panel
    - Custom Styling
    - Custom Javascript
    - Infinite Scroll for each widgted
    - Category integration
- Slider Revolution

# installation

## add to composer.json

```json
{
    "require": {
      "diazoxide/yii2-blog": "dev-master"
    }
}
```

## common configuration

```php
 'modules'=>[
     'blog' => [
         'class' => "diazoxide\blog\Module",
         'urlManager' => 'urlManager',
         'imgFilePath' => dirname(__DIR__) . '/public/uploads/img/blog/',
         'imgFileUrl' => '/uploads/img/blog/',
         // You can change any view file for each route
         'frontendViewsMap'=>[
             'blog/default/index'=>'@app/views/blog/index'
         ],
         // You can change any layout for each route
         'frontendLayoutMap'=>[
             'blog/default/view'=>'@app/views/layouts/main-with-two-sidebar',
             'blog/default/archive'=>'@app/views/layouts/main-with-right-sidebar',
         ],
         'homeTitle'=>'Blog title',
         'userModel' => "\app\models\User",
         'userPK' => 'id',
         'userName' => 'username',
         'showClicksInPost'=>false,
         'enableShareButtons' => true,
         'blogPostPageCount' => '10',
         'schemaOrg' => [
             'publisher' => [
                 'logo' => '/path/to/logo.png',
                 'logoWidth' => 200,
                 'logoHeight' => 47,
                 'name' => "Blog title",
                 'phone' => '+1 800 488 80 85',
                 'address' => 'Address 13/5'
             ]
         ]
     ],
 ]
```

## backend configuration

```php
$config['modules']['blog']['controllerNamespace'] = 'diazoxide\blog\controllers\backend';
```

# Migration

> after install run migration command

```bash
php yii migrate --migrationPath=@vendor/diazoxide/yii2-blog/migrations
```

# Customisation

Module is flexible. You can customize everything in module

## UrlManager and custom routes

> archive url in frontend

https://blog.com/archive

> category url in frontend

https://blog.com/category/politics

> post url in frontend like a wordpress and seo friendly

https://blog.com/2019/11/21/your-post-slug

```php
 'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        //F.E. https://blog.com/category/politics
        '/category/<slug>' => '/blog/default/archive',
        
        //F.E. https://blog.com/archive
        '/archive' => '/blog/default/archive',
        
        //F.E. https://blog.com/2019/11/21/your-post-slug
        [
            'pattern' => '<year:\d{4}>/<month:\d{2}>/<day:\d{2}>/<slug>',
            'route' => '/blog/default/view',
            'suffix' => '/'
        ],
    ],
],
```

## Navigation elements

> Integration backend navigation menu

Simply you can use Module builtin function "getNavigation"

>> Yii::$app->getModule('blog')->getNavigation()

```php
echo Nav::widget([
    'encodeLabels' => false,
    'options' => ['class' => $class],
    'items' => Yii::$app->getModule('blog')->getNavigation()
]);
```

## View files customisation

In config file you can simply customise any view file. 

You can create your custom view files and connect it from config.php

```php
'frontendViewsMap' => [
     'blog/default/index'=>'@app/views/blog/index',
     'blog/default/index' => 'index',
     'blog/default/view' => 'view',
     'blog/default/archive' => 'archive',
     'blog/default/book' => 'viewBook',
     'blog/default/chapter' => 'viewChapter',
     'blog/default/chapter-search' => 'searchBookChapter',
],
```

## Layout files customisations

```php
'frontendLayoutMap'=>[
    'blog/default/view'=>'@app/views/layouts/my-custom-layout-1',
    'blog/default/archive'=>'@app/views/layouts/my-custom-layout-2',
    ...
],
```

# Support

## Contacts

 - Email: aaron.yor@gmail.com
 - Mobile: +374 (98) 47 11 11
 - Linkedin։ <https://www.linkedin.com/in/aaron-yor/>
 