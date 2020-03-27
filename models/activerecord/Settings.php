<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $value
 */
class Settings extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }


}
