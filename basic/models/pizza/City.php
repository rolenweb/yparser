<?php

namespace app\models\pizza;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property string $name
 * @property integer $state_id
 * @property string $description
 * @property double $lon
 * @property double $lat
 * @property integer $created_at
 * @property integer $updated_at
 */
class City extends \yii\db\ActiveRecord
{
    public $countCompany;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
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
            [['state_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['lon', 'lat'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'state_id' => 'State ID',
            'description' => 'Description',
            'lon' => 'Lon',
            'lat' => 'Lat',
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

    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }

    public function getCompanies()
    {
        return $this->hasMany(Company::className(), ['city_id' => 'id'])->inverseOf('city');
    }
}
