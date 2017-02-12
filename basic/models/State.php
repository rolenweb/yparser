<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "state".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
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
            'code' => 'Code',
            'description' => 'Description',
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

    public static function idByCode($code)
    {
        return ArrayHelper::getValue(self::findOne(['code' => $code]), 'id');    
    }

    public function getCity()
    {
        return $this->hasMany(City::className(), ['state_id' => 'id'])->inverseOf('state');
    }

    public static function dd()
    {
        return ArrayHelper::map(self::find()->orderBy(['name' => SORT_ASC])->all(),'code','name');
    }

    public static function dd2()
    {
        return ArrayHelper::map(self::find()->orderBy(['name' => SORT_ASC])->all(),'id','name');
    }
}
