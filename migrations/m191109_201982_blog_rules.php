<?php
/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

use yii\db\Migration;
use yii\db\Schema;

/**
 * CLass m141208_201480_blog_init
 * @package diazoxide\blog\migrations
 *
 * Create blog tables.
 *
 * Will be created 4 tables:
 * - `{{%blog_category}}` - Blog category
 * - `{{%blog_post}}` -
 * - `{{%blog_comment}}` -
 * - `{{%blog_tag}}` -
 */
class m191109_201982_blog_rules extends Migration
{
    use \diazoxide\blog\traits\ModuleTrait;

    public $rules = [
        'diazoxide\blog\rbac\BlogAuthorRule',
    ];
    public $permissions = [
        ['BLOG_BULK_COMMENTS', 'Bulk blog comments'],
        ['BLOG_CONFIRM_ALL_COMMENTS', 'Confirm all blog comments'],
        ['BLOG_CREATE_CATEGORY', 'Create blog category'],
        ['BLOG_CREATE_COMMENT', 'Create blog comment'],
        ['BLOG_CREATE_POST', 'Create blog post'],
        ['BLOG_CREATE_TAG', 'Create blog tag'],
        ['BLOG_DELETE_ALL_COMMENTS', 'Delete all blog comments'],
        ['BLOG_DELETE_CATEGORY', 'Delete blog category'],
        ['BLOG_DELETE_COMMENT', 'Delete blog comment'],
        ['BLOG_DELETE_POST', 'Delete blog post'],
        ['BLOG_DELETE_TAG', 'Delete blog tag'],
        ['BLOG_UPDATE_CATEGORY', 'Update blog category'],
        ['BLOG_UPDATE_COMMENT', 'Update comment'],
        ['BLOG_UPDATE_OWN_POST', 'Update own blog post', ['blogIsAuthor']],
        ['BLOG_UPDATE_POST', 'Update blog post'],
        ['BLOG_UPDATE_TAG', 'Update blog tag'],
        ['BLOG_VIEW_CATEGORIES', 'View blog categories'],
        ['BLOG_VIEW_CATEGORY', 'View blog category'],
        ['BLOG_VIEW_COMMENT', 'View blog comment'],
        ['BLOG_VIEW_COMMENTS', 'View blog comments'],
        ['BLOG_VIEW_POST', 'View blog post'],
        ['BLOG_VIEW_POSTS', 'View blog posts'],
        ['BLOG_VIEW_TAG', 'View blog tag'],
        ['BLOG_VIEW_TAGS', 'View blog tags'],

        ['BLOG_VIEW_WIDGET_TYPES','View widget types'],
        ['BLOG_CREATE_WIDGET_TYPE','Create widget type'],
        ['BLOG_UPDATE_WIDGET_TYPE','Update widget type'],
        ['BLOG_DELETE_WIDGET_TYPE','Delete widget type'],

        ['BLOG_UPDATE_OWN_POST_BOOK_CHAPTER', 'Update own post book chapter', ['blogIsAuthor']],
        ['BLOG_UPDATE_POST_BOOK_CHAPTER', 'Update post book chapter'],
        ['BLOG_CREATE_OWN_POST_BOOK_CHAPTER', 'Create own post book chapter', ['blogIsAuthor']],
        ['BLOG_CREATE_POST_BOOK_CHAPTER', 'Create post book chapter'],
        ['BLOG_DELETE_OWN_POST_BOOK_CHAPTER', 'Delete own post book chapter', ['blogIsAuthor']],
        ['BLOG_DELETE_POST_BOOK_CHAPTER', 'Delete post book chapter'],

        ['BLOG_UPDATE_OWN_POST_BOOK', 'Update own post book', ['blogIsAuthor']],
        ['BLOG_UPDATE_POST_BOOK', 'Update post book'],
        ['BLOG_CREATE_OWN_POST_BOOK', 'Create own post book', ['blogIsAuthor']],
        ['BLOG_CREATE_POST_BOOK', 'Create post book'],
        ['BLOG_DELETE_OWN_POST_BOOK', 'Delete own post book', ['blogIsAuthor']],
        ['BLOG_DELETE_POST_BOOK', 'Delete post book'],
    ];

    public $roles = [
        ['BLOG_ADMIN', 'Blog Administrator', [
            'BLOG_BULK_COMMENTS',
            'BLOG_CONFIRM_ALL_COMMENTS',
            'BLOG_CREATE_CATEGORY',
            'BLOG_CREATE_COMMENT',
            'BLOG_CREATE_POST',
            'BLOG_CREATE_TAG',
            'BLOG_DELETE_ALL_COMMENTS',
            'BLOG_DELETE_CATEGORY',
            'BLOG_DELETE_COMMENT',
            'BLOG_DELETE_POST',
            'BLOG_DELETE_TAG',
            'BLOG_UPDATE_CATEGORY',
            'BLOG_UPDATE_COMMENT',
            'BLOG_UPDATE_OWN_POST',
            'BLOG_UPDATE_POST',
            'BLOG_UPDATE_TAG',
            'BLOG_VIEW_CATEGORIES',
            'BLOG_VIEW_CATEGORY',
            'BLOG_VIEW_COMMENT',
            'BLOG_VIEW_COMMENTS',
            'BLOG_VIEW_POST',
            'BLOG_VIEW_POSTS',
            'BLOG_VIEW_TAG',
            'BLOG_VIEW_TAGS',
            'BLOG_UPDATE_OWN_POST_BOOK_CHAPTER',
            'BLOG_CREATE_OWN_POST_BOOK_CHAPTER',
            'BLOG_DELETE_OWN_POST_BOOK_CHAPTER',
            'BLOG_UPDATE_OWN_POST_BOOK',
            'BLOG_UPDATE_POST_BOOK',
            'BLOG_CREATE_OWN_POST_BOOK',
            'BLOG_CREATE_POST_BOOK',
            'BLOG_DELETE_OWN_POST_BOOK',
            'BLOG_DELETE_POST_BOOK',
            'BLOG_VIEW_WIDGET_TYPES',
            'BLOG_CREATE_WIDGET_TYPE',
            'BLOG_UPDATE_WIDGET_TYPE',
            'BLOG_DELETE_WIDGET_TYPE',
        ]],
        ['BLOG_MANAGER', 'Blog Manager', [
            'BLOG_BULK_COMMENTS',
            'BLOG_CONFIRM_ALL_COMMENTS',
            'BLOG_CREATE_CATEGORY',
            'BLOG_CREATE_COMMENT',
            'BLOG_CREATE_POST',
            'BLOG_CREATE_TAG',
            'BLOG_DELETE_ALL_COMMENTS',
            'BLOG_DELETE_CATEGORY',
            'BLOG_DELETE_COMMENT',
            'BLOG_DELETE_POST',
            'BLOG_DELETE_TAG',
            'BLOG_UPDATE_CATEGORY',
            'BLOG_UPDATE_COMMENT',
            'BLOG_UPDATE_OWN_POST',
            'BLOG_UPDATE_POST',
            'BLOG_UPDATE_TAG',
            'BLOG_VIEW_CATEGORIES',
            'BLOG_VIEW_CATEGORY',
            'BLOG_VIEW_COMMENT',
            'BLOG_VIEW_COMMENTS',
            'BLOG_VIEW_POST',
            'BLOG_VIEW_POSTS',
            'BLOG_VIEW_TAG',
            'BLOG_VIEW_TAGS',
            'BLOG_UPDATE_OWN_POST_BOOK_CHAPTER',
            'BLOG_CREATE_OWN_POST_BOOK_CHAPTER',
            'BLOG_DELETE_OWN_POST_BOOK_CHAPTER',
            'BLOG_UPDATE_OWN_POST_BOOK',
            'BLOG_UPDATE_POST_BOOK',
            'BLOG_CREATE_OWN_POST_BOOK',
            'BLOG_CREATE_POST_BOOK',
            'BLOG_DELETE_OWN_POST_BOOK',
            'BLOG_DELETE_POST_BOOK',
        ]],
        ['BLOG_EDITOR', 'Blog Editor', [
            'BLOG_CREATE_POST',
            'BLOG_UPDATE_OWN_POST',
            'BLOG_VIEW_CATEGORIES',
            'BLOG_VIEW_CATEGORY',
            'BLOG_VIEW_COMMENT',
            'BLOG_VIEW_COMMENTS',
            'BLOG_VIEW_POST',
            'BLOG_VIEW_POSTS',
            'BLOG_VIEW_TAG',
            'BLOG_VIEW_TAGS',
        ]],
    ];

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function up()
    {
        //$this->removeAllAuthItems();
        $this->registerRules();
        $this->registerPermissions();
        $this->registerRoles();
    }

    /**
     * @throws Exception
     */
    public function registerPermissions()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->permissions as $permission) {

            $p = $auth->createPermission($permission[0]);
            $p->description = $permission[1];
            if (isset($permission[2])) {
                foreach ($permission[2] as $ruleName) {
                    $p->ruleName = $ruleName;
                }
            }
            $auth->remove($p);
            $auth->add($p);
        }


    }

    /**
     * @throws Exception
     */
    public function registerRoles()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->roles as $role) {
            $r = $auth->createRole($role[0]);
            $r->description = $role[1];
            $auth->remove($r);
            $auth->add($r);
            if (isset($role[2])) {
                foreach ($role[2] as $permissionName) {
                    $permission = $auth->getPermission($permissionName);
                    $auth->addChild($r, $permission);
                }
            }

        }
    }


    /**
     * @throws Exception
     */
    public function registerRules()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->rules as $key => $rule) {
            $r = new $rule();
            $auth->remove($r);
            $auth->add($r);
        }
    }

    /**
     * @throws Exception
     */
    public function removeAllAuthItems()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {

    }
}
