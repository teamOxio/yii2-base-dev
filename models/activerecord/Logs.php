<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $particulars
 * @property string $time
 * @property string $ip
 * @property string $useragent
 * @property string|null $type
 * @property string|null $data
 * @property int|null $ip_country_id
 *
 * @property Countries $country
 * @property Users $user
 */
class Logs extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ip', 'useragent'], 'required'],
            [['user_id', 'ip_country_id'], 'integer'],
            [['particulars'], 'string'],
            [['time','data'], 'safe'],
            [['ip'], 'string', 'max' => 64],
            [['useragent', 'type'], 'string', 'max' => 800],
            [['ip_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['ip_country_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'particulars' => 'Particulars',
            'time' => 'Time',
            'ip' => 'Ip',
            'useragent' => 'Useragent',
            'type' => 'Type',
            'data' => 'Data',
            'ip_country_id' => 'Country ID',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'ip_country_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
