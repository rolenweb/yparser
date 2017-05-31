<?php

namespace app\models\pizza;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $ypid
 * @property string $title
 * @property string $phone
 * @property string $address
 * @property double $lat
 * @property double $lon
 * @property integer $created_at
 * @property integer $updated_at
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
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
            [['city_id', 'ypid', 'created_at', 'updated_at'], 'integer'],
            [['ypid'],'unique'],
            [['lat', 'lon'], 'number'],
            [['title', 'phone', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'ypid' => 'Ypid',
            'title' => 'Title',
            'phone' => 'Phone',
            'address' => 'Address',
            'lat' => 'Lat',
            'lon' => 'Lon',
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
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function getHours()
    {
        return $this->hasMany(CompanyHours::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function getExtraPhones()
    {
        return $this->hasMany(CompanyExtraPhones::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function getCategories()
    {
        return $this->hasMany(CompanyCategory::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function getInfo()
    {
        return $this->hasMany(CompanyOtherInfo::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function getPaymetMethods()
    {
        return $this->hasMany(CompanyPaymentMethod::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function getWeblinks()
    {
        return $this->hasMany(CompanyWeblink::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function formatHours()
    {
        if (empty($this->hours)) {
            return;
        }
        $out = [];
        foreach ($this->hours as $hour) {
            $out[$hour->type][$hour->day] = [
                'start' => $hour->start,
                'end' => $hour->end
            ];
        }
        return $out;
    }
}
