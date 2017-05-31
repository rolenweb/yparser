<?php

namespace app\models\pizza;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "company_hours".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $type
 * @property integer $day
 * @property string $start
 * @property string $end
 * @property integer $created_at
 * @property integer $updated_at
 */
class CompanyHours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_hours';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'day', 'created_at', 'updated_at'], 'integer'],
            [['start', 'end'], 'safe'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'type' => 'Type',
            'day' => 'Day',
            'start' => 'Start',
            'end' => 'End',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
