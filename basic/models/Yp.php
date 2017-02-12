<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use app\commands\tools\CurlClient;

/**
 * ContactForm is the model behind the contact form.
 */
class Yp extends Model
{
    public $city;
    public $state_code;
    public $state_name;
    public $keyword;

    public function rules()
    {
        return [
            [['city','state_code','state_name','keyword'], 'string'],
             [['city','state_code','keyword'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'city' => 'City',
            'state_code' => 'State code',
            'state_name' => 'State name',
            'keyword' => 'Keyword',
        ];
    }

    public function loadParameters($parameters)
    {
        $this->city = (empty($parameters['city']) === false) ? $parameters['city'] : null;
        $this->state_code = (empty($parameters['state_code']) === false) ? $parameters['state_code'] : null;
        $this->keyword = (empty($parameters['keyword']) === false) ? $parameters['keyword'] : null;
        $this->state_name = (empty($parameters['state_name']) === false) ? $parameters['state_name'] : null;
        return $this;
    }
    
    public function parse()
    {
        
        $result = [];
        
        $url = $this->genUrl($this->keyword,$this->city.', '.$this->state_code);

        $client = new CurlClient();
        $content = $client->parsePage2($url);
        
        $vCards = $client->parseProperty($content,'html','div.search-results div.result div.v-card',null,null);
        if (empty($vCards)) {
            return;
        }
        $parsed = 0;
        foreach ($vCards as $vCard) {
            $street = $client->parseProperty($vCard,'string','div.info-primary p.adr span.street-address',null,null);
            $ypid = $this->getYpid($client->parseProperty($vCard,'attribute','h3.n a.business-name',null,'href')); 
            if (empty($street[0]) === false && empty($ypid) === false) {
                $locality = $client->parseProperty($vCard,'string','div.info-primary p.adr span.locality',null,null);
                $addressRegion = $client->parseProperty($vCard,'string','div.info-primary p.adr span[itemprop = "addressRegion"]',null,null); 
                if (empty($locality[0]) === false && empty($addressRegion[0]) === false) {
                    if (stripos($locality[0],$this->city) !== false && stripos($addressRegion[0],$this->state_code) !== false) {
                        $title = $client->parseProperty($vCard,'string','h3.n a.business-name',null,null);  
                        $postalCode = $client->parseProperty($vCard,'string','div.info-primary p.adr span[itemprop = "postalCode"]',null,null); 

                        $telephone = $client->parseProperty($vCard,'string','div.info-primary div[itemprop = "telephone"]',null,null); 

                        $thumbnail = $client->parseProperty($vCard,'attribute','div.media-thumbnail a img',null,'src'); 
                        $categories = $client->parseProperty($vCard,'string','div.info-secondary div.categories a',null,null); 
                        $website = $client->parseProperty($vCard,'attribute','div.info-secondary div.links a.track-visit-website',null,'href'); 
                        
                        $result[$parsed]['ypid'] = $ypid;
                        $result[$parsed]['title'] = (empty($title[0]) === false) ? trim($title[0]) : null;
                        $result[$parsed]['image'] = (empty($thumbnail[0]) === false) ? trim($thumbnail[0]) : null;
                        $result[$parsed]['street'] = (empty($street[0]) === false) ? trim($street[0]) : null;
                        $result[$parsed]['city'] = $this->city;
                        $result[$parsed]['state'] = $this->state_code;
                        $result[$parsed]['postal'] = (empty($postalCode[0]) === false) ? trim($postalCode[0]) : null;
                        $result[$parsed]['phone'] = (empty($telephone[0]) === false) ? trim($telephone[0]) : null;
                        $result[$parsed]['category'] = (empty($categories) === false) ? implode(', ', $categories) : null;
                        $result[$parsed]['website'] = (empty($website[0]) === false) ? trim($website[0]) : null;
                        $parsed++;
                    }
                }
                
            }
        }
        return [
            'total' => count($vCards),
            'data' => $result,
        ];        
    }

    public function genUrl($search, $loc, $page = 1)
    {
        if (empty($search) === false && empty($loc) === false) {
            if ($page == 1 || empty($page)) {
                $url = 'http://www.yellowpages.com/search?search_terms='.urlencode($search).'&geo_location_terms='.urlencode($loc);
            }else{
                $url = 'http://www.yellowpages.com/search?search_terms='.urlencode($search).'&geo_location_terms='.urlencode($loc).'&page='.$page;
            }
            return $url;
        }
    }

    public function getYpid($in)
    {
        if (empty($in[0])) {
            return;
        }
        $parse_url = parse_url($in[0]);
        if (empty($parse_url['query'])) {
            return;
        }
        parse_str($parse_url['query']);
        return (empty($lid) === false) ? $lid : null;
    }
}
