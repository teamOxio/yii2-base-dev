<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;
use app\common\Constants;

/**
 * This is the model class for table "background_tasks".
 *
 * @property int $id
 * @property int $attempts
 * @property string $type
 * @property string|null $data
 * @property string $time
 * @property string $updated_on
 * @property string|null $response
 * @property string|null $reference
 */
class BackgroundTasks extends BaseActiveRecord
{
    public $attempts;
    public function beforeValidate()
    {
        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'background_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['response'], 'string'],
            [['attempts'],'integer'],
            [['data','time', 'updated_on'], 'safe'],
            [['type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'data' => 'Data',
            'time' => 'Time',
            'updated_on' => 'Updated On',
            'response' => 'Response',
            'reference' => 'Reference',
            'attempts' => 'Attempts',
        ];
    }

    /**
     * {@inheritdoc}
     * @return BackgroundTasksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BackgroundTasksQuery(get_called_class());
    }


}
