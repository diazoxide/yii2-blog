<?php

/**
 * Project: yii2-blog for internal using
 * Author: diazoxide
 * Copyright (c) 2018.
 */

namespace diazoxide\blog\traits;

use diazoxide\blog\Module;

interface IActiveStatus
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_ARCHIVE = -1;
}

trait StatusTrait
{
    public static function getStatusList()
    {
        return [
            IActiveStatus::STATUS_INACTIVE => Module::t('', 'STATUS_INACTIVE'),
            IActiveStatus::STATUS_ACTIVE => Module::t('', 'STATUS_ACTIVE'),
            IActiveStatus::STATUS_ARCHIVE => Module::t('', 'STATUS_ARCHIVE')
        ];
    }

    public function getStatusList2()
    {
        return self::getStatusList();
    }

    public function getStatus($nullLabel = '')
    {
        $statuses = static::getStatusList();
        return (isset($this->status) && isset($statuses[$this->status])) ? $statuses[$this->status] : $nullLabel;
    }
}
