<?php
namespace diazoxide\blog\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class BlogAuthorRule extends Rule
{
    public $name = 'blogIsAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
       // var_dump($params);
        return isset($params['model']) ? $params['model']->user_id == $user : false;
    }
}