<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Position;
use app\models\City;
use app\models\Company;
use app\models\Yp;

use app\commands\tools\CurlClient;

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
        $yp = new Yp();
    	for (;;) { 

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

            $state = $city->state;

            if (empty($state)) {
                $this->error('The state is not found');
                if (empty($position->next)) {
                    $this->error('The city is finished');
                    die;
                }
                continue;
            }

            $this->whisper('Select city: '.$city->name.', '.$state->code);

            $parameters = [
                'city' => $city->name,
                'state_code' => $city->state->code,
                'keyword' => $keyword->key
            ];
            
            $yp->loadParameters($parameters);
            if (!$yp->validate()) {
                foreach ($yp->getErrors() as $er) {
                    $this->error($er[0]);
                    return;
                }
            }
            
            $result = $yp->parse();

            if (empty($result['data'])) {
                $this->error('The result is null: city ID - '.$city->id);
                if (empty($position->next)) {
                    $this->error('The city is finished');
                    die;
                }
                continue;
            }

            $this->whisper('Found '.count($result['data']). ' companies');

            foreach ($result['data'] as $item) {
                $company = new Company();
                $company->city_id = $city->id;
                $company->keyword_id = $keyword->id;
                $company->attributes = $item;
                if ($company->save()) {
                    # code...
                }else{
                    foreach ($company->getErrors() as $er) {
                        $this->error($er[0]);
                    }       
                }
            }
            if (empty($position->next)) {
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

}
