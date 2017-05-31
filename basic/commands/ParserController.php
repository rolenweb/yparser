<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

use app\models\Position;
use app\models\Yp2;

//pizza
use app\models\pizza\City;
use app\models\pizza\State;
use app\models\pizza\Company;
use app\models\pizza\CompanyHours;
use app\models\pizza\CompanyCategory;
use app\models\pizza\CompanyExtraPhones;
use app\models\pizza\CompanyPaymentMethod;
use app\models\pizza\CompanyWeblink;
use app\models\pizza\CompanyOtherInfo;
//pizza


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $yp = new Yp2();

    	for ($iter = 0;$iter < 1000; $iter++) { 

    		$start = time();

            $position = Position::current();

            if (empty($position)) {
                $this->error('The position is not found');
                return;
            }

            $keyword = $position->keyword;
            
            $this->whisper('Select key: '.$keyword->key);

            if (empty($keyword)) {
                $this->error('The keyword is not found');
                return;
            }

            $city = $position->city;

            if (empty($city)) {
                $this->error('The city is not found');
                return;
            }
            $this->whisper('Select city: '.$city->name);

            $state = $city->state;

            if (empty($state)) {
                $this->error('The state is not found');
                if (empty($position->next)) {
                    $this->error('The city is finished');
                    die;
                }
                continue;
            }
            $this->whisper('Select state: '.$state->code);

            $yp->setDefault();
            $yp->setCity($city->name);
            $yp->setState($state->code);
            $yp->setQuery($keyword->key);

            try {
                $yp->parseLinks();
                $i = 0;
                $total = count($yp->linksFirm);
                $this->whisper('Parse: '.$total);
                Console::startProgress($i, $total);
                foreach ($yp->linksFirm as $n => $link) {
                    Console::updateProgress(++$i, $total);
                    $firmData = $yp->parseFirm($link['url']);
                    $company = new Company();
                    $company->ypid = ArrayHelper::getValue($firmData,'ypid');
                    $company->title = ArrayHelper::getValue($firmData,'title');
                    $company->address = ArrayHelper::getValue($firmData,'address');
                    $company->phone = ArrayHelper::getValue($firmData,'phone');
                    $company->lat = ArrayHelper::getValue($firmData,'geo.lat');
                    $company->lon = ArrayHelper::getValue($firmData,'geo.lon');

                    if ($firmData['city'] == $city->name) {
                        $company->city_id = $city->id;
                    }else{
                        $state = State::findOne(['code' => $firmData['state']]);
                        if (empty($state)) {
                            $this->error($firmData['state'].' state is not found');
                            continue;
                        }
                        $otherCity = City::find()
                            ->joinWith(['state'])
                            ->where(
                                [
                                    'and',
                                        [
                                            'city.name' => $firmData['city']
                                        ],
                                        [
                                            'state.code' => $firmData['state']
                                        ]
                                ]
                            )->limit(1)->one();
                        if (empty($otherCity) === false) {
                            $company->city_id = $otherCity->id;  
                            $this->whisper('Other city: '.$firmData['city'].' is linked');  
                        }else{
                            $newCity = new City();
                            $newCity->name = $firmData['city'];
                            $newCity->state_id = $state->id;
                            $newCity->lat  = $company->lat;
                            $newCity->lon  = $company->lon;
                            if (!$newCity->save()) {
                                foreach ($newCity->errors as $error) {
                                    $this->error($error[0]);
                                }
                                continue;
                            }
                            $this->whisper('New city: '.$firmData['city'].' is saved');
                            $company->city_id = $newCity->id;    
                        }
                    }
                    if (!$company->save()) {
                        foreach ($company->errors as $error) {
                            $this->error($error[0]);
                        }
                        continue;
                    }

                    if (empty($firmData['hours']) === false) {
                        foreach ($firmData['hours'] as $type => $days) {
                            if (empty($days)) {
                                continue;
                            }
                            foreach ($days as $nDay => $value) {
                                $companyHours = new CompanyHours();
                                $companyHours->company_id = $company->id;
                                $companyHours->type = $type;
                                $companyHours->day = $nDay;
                                $companyHours->start = ArrayHelper::getValue($value,'start');
                                $companyHours->end = ArrayHelper::getValue($value,'end');
                                if (!$companyHours->save()) {
                                    foreach ($companyHours->errors as $error) {
                                        $this->error($error[0]);
                                    }
                                }
                            }
                        }
                    }

                    if (empty($firmData['extra_phones']) === false) {
                        foreach ($firmData['extra_phones'] as $phone) {
                            $extraPhones = new CompanyExtraPhones();
                            $extraPhones->company_id = $company->id;
                            $extraPhones->phone = $phone;
                            if (!$extraPhones->save()) {
                                foreach ($extraPhones->errors as $error) {
                                    $this->error($error[0]);
                                }
                            }
                        }
                    }

                    if (empty($firmData['payment_method']) === false) {
                        foreach ($firmData['payment_method'] as $method) {
                            $paymentMethod = new CompanyPaymentMethod();
                            $paymentMethod->company_id = $company->id;
                            $paymentMethod->method = $method;
                            if (!$paymentMethod->save()) {
                                foreach ($paymentMethod->errors as $error) {
                                    $this->error($error[0]);
                                }
                            }
                        }
                    }

                    if (empty($firmData['weblinks']) === false) {
                        foreach ($firmData['weblinks'] as $link) {
                            $weblinks = new CompanyWeblink();
                            $weblinks->company_id = $company->id;
                            $weblinks->link = $link;
                            if (!$weblinks->save()) {
                                foreach ($weblinks->errors as $error) {
                                    $this->error($error[0]);
                                }
                            }
                        }
                    }

                    if (empty($firmData['categories']) === false) {
                        foreach ($firmData['categories'] as $category) {
                            $newCategory = new CompanyCategory();
                            $newCategory->company_id = $company->id;
                            $newCategory->title = $category;
                            if (!$newCategory->save()) {
                                foreach ($newCategory->errors as $error) {
                                    $this->error($error[0]);
                                }
                            }
                        }
                    }

                    if (empty($firmData['other_info']) === false) {
                        foreach ($firmData['other_info'] as $info) {
                            $newInfo = new CompanyOtherInfo();
                            $newInfo->company_id = $company->id;
                            $newInfo->info = $info;
                            if (!$newInfo->save()) {
                                foreach ($newInfo->errors as $error) {
                                    $this->error($error[0]);
                                }
                            }
                        }
                    }
                }
                Console::endProgress();
                
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            $next = $position->next;
            if (empty($next)) {
                $this->error('The city is finished');
                die;
            }
            
            $finish = time();
            $dif = $finish-$start;
            if ($dif < 3) {
                $sleep = rand(1,5);
                $this->whisper('Sleep '.$sleep.' sec');
                sleep($sleep);
            }
            //Console::clearScreen();
    	}
        
    }

    public function actionStart()
    {
        $yp = new Yp2();
        $yp->setDefault();
        $yp->setCity('New York');
        $yp->setState('NY');
        $yp->setQuery('pizza');

        try {
            //$yp->parseLinks();
            //$this->whisper('Parse: '.count($yp->linksFirm));
            $yp->linksFirm[] = [
                'url' => 'https://www.yellowpages.com/new-york-ny/mip/don-antonio-restaurant-470635766?lid=470635766'
            ];
            foreach ($yp->linksFirm as $n => $link) {
                $firmData = $yp->parseFirm($link['url']);

                var_dump($firmData);
                die;
            }
            
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
