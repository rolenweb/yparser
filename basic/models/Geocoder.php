<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\helpers\Console;

use app\commands\tools\CurlClient;

/**
 * ContactForm is the model behind the contact form.
 */
class Geocoder extends Model
{
    private $key;


    public function __construct($key)
    {
        $this->key = $key;   
    }

    public function geocode($address)
    {
        $client = new CurlClient();
        $content = $client->parsePage($this->url($address));
        if (empty($content)) {
            return;
        }

        $content_array = Json::decode($content);

        if ($content_array['status'] !== 'OK') {
            return [
                'error' => $content_array['status'],
            ];
        }

        $lat = ArrayHelper::getValue($content_array, 'results.0.geometry.location.lat');
        $lon = ArrayHelper::getValue($content_array, 'results.0.geometry.location.lng');
        
        return [
            'lat' => $lat,
            'lon' => $lon,
        ];
    }

    protected function url($address)
    {
        return 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.$this->key;
    }
}
