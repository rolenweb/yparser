<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

use app\models\City;
use app\models\Company;
use app\models\CompanyGeo;
use app\models\Geocoder;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GeocoderController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($n)
    {
        if (empty(Yii::$app->params['googleMapsGeocodingAPI'])) {
            $this->error('Google Maps Geocoding API is not set');
            return;
        }

        $geocoder = new Geocoder(Yii::$app->params['googleMapsGeocodingAPI']);

        $companies = Company::find()
                ->joinWith(['geo','city.state'])
                ->where(
                    [
                        'or',
                            [
                                'company_geo.lat' => null
                            ],
                            [
                                'company_geo.lon' => null
                            ]
                    ]
                )->limit($n)
                ->all();
        if (empty($companies)) {
            $this->success('Companis are not found');
            die;
        }

        $remain = $n;

    	foreach ($companies as $company){ 
    		$start = time();

            $address = $company->street.', '.$company->city->name.', '.$company->city->state->code.' '.$company->postal;

            

            $coordinates = $geocoder->geocode($address);
            if (empty($coordinates['error']) === false) {
                $this->error($coordinates['error']);
            }
            
            $this->whisper('City lat: '.$company->city->lat.' lon: '.$company->city->lon);
            $this->whisper($address);
            if (empty($coordinates['lat']) || empty($coordinates['lon'])) {
                $this->error('Coordinates is null');
                continue;
            }

            $this->whisper('Coordinates: lat: '.$coordinates['lat'].' lon '.$coordinates['lon']);
            
            $companyGeo = new CompanyGeo();
            $companyGeo->company_id = $company->id;
            $companyGeo->lat = $coordinates['lat'];
            $companyGeo->lon = $coordinates['lon'];
            if ($companyGeo->save()) {
                # code...
            }else{
                foreach ($companyGeo->getErrors() as $er) {
                    $this->error($er[0]);
                }
            }

            $finish = time();
            $dif = $finish-$start;
            if ($dif < 0) {
                $sleep = rand(1,2);
                $this->whisper('Sleep '.$sleep.' sec');
                sleep($sleep);
            }
            $remain--;
            $this->whisper('Remain: '.$remain);
    	}
        
    }

}
