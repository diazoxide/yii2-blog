# diazoxide/yii2-blog
<h4>Advanced Yii2 Blog module. Posts, Categories, Comments, Tags, With Slider Revolution, SEO tags, Social Tags</h4>

<h5>Features</h5>
<ul>
  <li>Blog Posts
  <ul>
    <li>Title</li>
    <li>Slug</li>
    <li>Multiple Categories for each post</li>
    <li>Brief</li>
    <li>Content
        <ul>
            <li>Custom Redactor/wysiwyg editor</li>
        </ul>
    </li>
    <li>Books
        <ul>
            <li>Chapters and sub Chapters</li>
            <li>Nested structure</li>
            <li>Nested breadcrumbs</li>
            <li>BBCode support</li>
            <li>Custom BBCode styling</li>
        </ul>
    </li>
  </ul>
  </li>
  <li>Blog Categories</li>
  <li>Blog Tags</li>
  <li>Blog Comments</li>
  <li>Custom Widgets
    <ul>
        <li>Dynamic widgets</li>
        <li>Custom backend panel</li>
        <li>Custom Styling</li>
        <li>Custom Javascript</li>
        <li>Infinite Scroll for each widgted</li>
        <li>Category integration</li>
    </ul>
  </li>
  <li>Slider Revolution</li>
</ul>

# installation
>add to composer.json
```
"diazoxide/yii2-blog": "dev-master"
```
>common configuration
```
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

>backend configuration

just add this line in your backend configuration file
```
$config['modules']['blog']['controllerNamespace'] = 'diazoxide\blog\controllers\backend';

```

# Custom urlManager rules for beautiful links
>archive url in frontend

https://blog.com/archive

>category url in frontend

https://blog.com/category/politics

>post url in frontend like a wordpress and seo friendly

https://blog.com/2019/11/21/your-post-slug

```
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