<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 24.04
 * Time: 23:33
 */

namespace diazoxide\blog\behaviors\DataOptionsBehavior;

use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * @author Diazoxide
 *
 * @property ActiveRecord $owner
 */
class Behavior extends \yii\base\Behavior
{

    public $data_model;
    public $owner_attribute = "owner_id";
    public $name_attribute = "name";
    public $value_attribute = "value";

    protected $_data = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            /*ActiveRecord::EVENT_BEFORE_UPDATE   => 'beforeSave',
            ActiveRecord::EVENT_AFTER_DELETE    => 'afterDelete',
            ActiveRecord::EVENT_BEFORE_DELETE   => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_INSERT   => 'beforeSave',*/
        ];
    }

    /**
     * After save
     * @throws Exception
     */
    public function afterSave()
    {
        foreach ($this->_data as $data) {
            $this->setDataValue($data[0], $data[1], $data[2]);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws Exception
     */
    public function getData()
    {
        return $this->owner->hasMany($this->data_model, [$this->owner_attribute => $this->getPrimaryKey()]);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getPrimaryKey()
    {
        $primaryKey = $this->owner->primaryKey();
        if (!isset($primaryKey[0])) {
            throw new Exception('"' . $this->owner->className() . '" must have a primary key.');
        }
        return $primaryKey[0];
    }


    /**
     * @param $name
     * @param null $default
     * @return array|null|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     * @throws Exception
     */
    public function getDataValue($name, $default = null)
    {
        $query = $this->getData()->andWhere([$this->name_attribute => $name]);

        if ($query->count() == 1) {
            return $query->one();
        } elseif ($query->count() > 1) {
            return $query->all();
        }

        return $default;
    }

    /**
     * Saving Dynamic data to db
     * @param $name
     * @param $value
     * @param bool $overwrite
     * @return array|bool
     * @throws Exception
     */
    public function setDataValue($name, $value, $overwrite = true)
    {
        if ($this->owner->isNewRecord) {
            $this->_data[] = [$name, $value, $overwrite];
            return true;
        }

        $errors = [];
        $data = $this->getDataValue($name);

        if (!is_array($value)) {
            $value = [$value];
        }

        if (!$data || !$overwrite) {

            foreach ($value as $item) {
                /** @var ActiveRecord $model */
                $model = new $this->data_model;
                $model->{$this->owner_attribute} = $this->owner->id;
                $model->{$this->name_attribute} = $name;
                $model->{$this->value_attribute} = (string)$item;
                if (!$model->save()) {
                    $errors[] = $model->errors;
                }
            }

        } elseif ($overwrite) {

            if (!is_array($data)) {
                $data = [$data];
            }

            foreach ($value as $key => $item) {
                $model = isset($data[$key]) ? $data[$key] : new $this->data_model;
                $model->{$this->owner_attribute} = $this->owner->id;
                $model->{$this->name_attribute} = $name;
                $model->{$this->value_attribute} = (string)$item;
                if (!$model->save()) {
                    $errors[] = $model->errors;
                }
            }
        }

        if (!empty($errors)) {
            return $errors;
        }

        return true;
    }


    /**
     * @param $name
     * @param $value
     * @return \yii\db\ActiveQuery
     */
    public function findByData($name, $value)
    {
        return $this->owner::find()
            ->joinWith(['data data'])
            ->andWhere(
                [
                    "data.{$this->name_attribute}" => $name,
                    "data.{$this->value_attribute}" => $value
                ]
            );
    }
}