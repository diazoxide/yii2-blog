<?php
/**
 * Created by PhpStorm.
 * User: Yordanyan
 * Date: 25.04
 * Time: 10:03
 */

namespace diazoxide\blog\behaviors\DataOptionsBehavior;

class DataModel extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string','max'=>255],
            [['value'], 'string']
        ];
    }


}