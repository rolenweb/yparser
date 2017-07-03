<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "position".
 *
 * @property integer $id
 * @property integer $keyword_id
 * @property integer $city_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword_id', 'city_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['keyword_id', 'city_id'], 'required'],
            ['status', 'default', 'value' => 1],
            ['keyword_id', 'unique'],
            [['model_city'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword_id' => 'Keyword ID',
            'city_id' => 'City ID',
            'status' => 'Status',
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

    public function getCity()
    {
        $model_city = $this->model_city;
        return $this->hasOne($model_city::className(), ['id' => 'city_id']);
    }

    public function getKeyword()
    {
        return $this->hasOne(Keyword::className(), ['id' => 'keyword_id']);
    }

    public function statusName()
    {
        if ($this->status === 1) {
            return 'Wating';
        }
        if ($this->status === 2) {
            return 'Parsing';
        }
        if ($this->status === 3) {
            return 'Finished';
        }
    }

    public static function ddStatus()
    {
        return [
            1 => 'Wating',
            2 => 'Parsing',
            3 => 'Finished'
        ];
    }

    public function getNext()
    {
        $model_city = $this->model_city;
        $nextCity = $model_city::find()->where(['>','id',$this->city_id])->limit(1)->one();
        if (empty($nextCity)) {
            return;
        }
        $this->city_id = $nextCity->id;
        $this->save();
        return $this;
    }

    public static function current()
    {
        return self::find()->where(['status' => 2])->limit(1)->one();
    }

    public function getProcess()
    {
        $model_city = $this->model_city;

        $totalCity = $model_city::find()->count();

        $usedCity = $model_city::find()->where(['<','id',$this->city_id])->count();
        $process = (empty($usedCity) === 0) ? 0 : round($usedCity/$totalCity*100);
        return $process;
    }
}
