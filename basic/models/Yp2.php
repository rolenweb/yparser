<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link;
use Spatie\Regex\Regex;

use app\models\City;

class Yp2 extends Model
{
    public $baseUri = 'https://www.yellowpages.com';
    public $city;
    public $state;
    public $query;
    public $linksPage = [];
    public $linksFirm = [];

    public function __construct($config = [])
    {
        $this->scenario = (empty($config['scenario']) === false) ? $config['scenario'] : 'default';
        
    }
    
    public function rules()
    {
        return [
            
        ];
    }

    public function attributeLabels()
    {
        return [
            
            
        ];
    }

    public function setDefault()
    {
        $this->linksFirm = [];
        $this->linksPage = [];
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function parse()
    {
        $links = $this->parseLinks();
        var_dump($this->linksFirm);
        die;
        //$links = [/*'https://2gis.ru/novosibirsk/firm/141265770256809?queryState=center%2F83.017524%2C55.002489%2Fzoom%2F17'*/'https://2gis.ru/novosibirsk/firm/70000001025575151?queryState=center%2F82.922705%2C55.044212%2Fzoom%2F17'];
        $this->parseFirms($this->linksFirm);
        die;
    }

    public function parseLinks($url = null)
    {
        $client = new Client();
        $url = (empty($url) === false) ? $url : $this->genUrl();
        try {
            $response = $client->request('GET', $url);
            $crawler = new Crawler($response->getBody()->getContents());
            $linksFirm = $crawler->filter('div.organic div.result h2.n a.business-name')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            
            $linksPage = $crawler->filter('div.pagination ul li a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });

            $this->addLinksPage($linksPage);
            $this->addLinksFirm($linksFirm);
            $this->markLinkPage($url);
            $nextUrl = $this->nextLinkPage();
            
            if (empty($nextUrl) === false) {
                $this->parseLinks($nextUrl);
            }
            return;
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
            
        }
    }

    public function parseFirm($url)
    {
        $client = new Client();
        try {
            $response = $client->request('GET', $url);
            $crawler = new Crawler($response->getBody()->getContents());

            $title = $crawler->filter('article.business-card div.sales-info h1')->each(function (Crawler $node, $i) {
                return $node->text();
            });

            $address = $crawler->filter('article.business-card div.contact p.address span')->each(function (Crawler $node, $i) {
                    return $node->text();
            });

            $city = str_replace(',','',ArrayHelper::getValue($address,'1',''));
            $state = ArrayHelper::getValue($address,'2');

            if (empty($address) === false) {
                $address = $this->address($address);
            }

            $phone = $crawler->filter('article.business-card div.contact p.phone')->each(function (Crawler $node, $i) {
                    return $node->text();
            });

            $business_info_hours = $crawler->filter('section#business-info div.open-details')->each(function (Crawler $node, $i) {
                    return $node->html();
                });

            if (empty($business_info_hours) === false) {
                $hours = $this->getHours($business_info_hours);
            }

            $extra_phones = $crawler->filter('section#business-info dd.extra-phones p span:nth-child(2)')->each(function (Crawler $node, $i) {
                    return $node->html();
            });

            $payment_method = $crawler->filter('section#business-info dd.payment')->each(function (Crawler $node, $i) {
                    return $node->html();
            });
            if (empty($payment_method) === false) {
                $payment_method = $this->paymentMethod($payment_method[0]);
            }

            $weblinks = $crawler->filter('section#business-info dd.weblinks p a')->each(function (Crawler $node, $i) {
                    return $node->attr('href');
            });

            $categories = $crawler->filter('section#business-info dd.categories span a')->each(function (Crawler $node, $i) {
                    return $node->text();
            });

            $other_info = $crawler->filter('section#business-info dd.other-information')->each(function (Crawler $node, $i) {
                    return $node->html();
            });

            $lat = $crawler->filter('div#bpp-static-map')->each(function (Crawler $node, $i) {
                    return $node->attr('data-lat');
            });
            $lon = $crawler->filter('div#bpp-static-map')->each(function (Crawler $node, $i) {
                    return $node->attr('data-lng');
            });
            
            return [
                'ypid' => $this->ypid($url),
                'title' => ArrayHelper::getValue($title,'0'),
                'city' => $city,
                'state' => $state,
                'address'  => $address,
                'phone' => ArrayHelper::getValue($phone,'0'),
                'geo' => [
                    'lat' => ArrayHelper::getValue($lat,'0'),
                    'lon' => ArrayHelper::getValue($lon,'0'),
                ],
                'hours' => (empty($hours) === false) ? $hours : null,
                'extra_phones' => $extra_phones,
                'payment_method' => (empty($payment_method) === false) ? $payment_method : null,
                'weblinks' => $weblinks,
                'categories' => $categories,
                'other_info' => $other_info,
            ];

            } catch (RequestException $e) {
                throw new Exception($e->getMessage());
                
            }
        
    }

    public function addLinksFirm($list)
    {
        if (empty($list)) {
            return;
        }
        foreach ($list as $item) {
            if (array_search($this->baseUri.$item, ArrayHelper::getColumn($this->linksFirm,'url')) === false) {
                $this->linksFirm[] = [
                    'status' => 'wating',
                    'url' => $this->baseUri.$item,
                ];
            }
        }
        return;
    }

    public function addLinksPage($list)
    {
        if (empty($list)) {
            return;
        }
        foreach ($list as $item) {
            if (array_search($this->baseUri.$item, ArrayHelper::getColumn($this->linksPage,'url')) === false) {
                $this->linksPage[] = [
                    'status' => 'wating',
                    'url' => $this->baseUri.$item,
                ];
            }
        }
        return;
    }

    public function markLinkFirm($url)
    {
        if (empty($this->linksFirm)) {
            return;
        }
        foreach ($this->linksFirm as $link) {
            if ($url == $link['url']) {
                $link['status'] = 'parsed';
            }
        }
        return;
    }

    public function markLinkPage($url)
    {
        if (empty($this->linksPage)) {
            return;
        }
        foreach ($this->linksPage as $n => $link) {
            if ($url == $link['url']) {
                $this->linksPage[$n]['status'] = 'parsed';
            }
        }
        return;
    }

    public function nextLinkPage()
    {
        if (empty($this->linksPage)) {
            return;
        }
        foreach ($this->linksPage as $link) {
            if ($link['status'] === 'wating') {
                return $link['url'];
            }
        }
        return;
    }

    public function genUrl()
    {
        if (empty($this->city)) {
            throw new Exception('City is null');
        }
        if (empty($this->query)) {
            throw new Exception('Query is null');
        }
        if (empty($this->state)) {
            throw new Exception('State is null');
        }
        
        return $this->baseUri.'/search?search_terms='.urlencode($this->query).'&geo_location_terms='.urlencode($this->city.', '.$this->state);
    }

    public function getСoordinates($str)
    {
        $search = ['(',')','POINT'];
        $str = str_replace($search, '', $str);
        return explode(' ', $str);
    }

    public function workHours($str)
    {
        $out = [];
        $results = Regex::matchAll('/([0-9][0-9]:[0-9][0-9])/', $str)->results();

        for ($i=0; $i <7; $i++) { 
            $out['start'][$i] = $results[0]->group(1);
            $out['end'][$i] = $results[1]->group(1);
        }

        return $out;
    }

    public function dayWeek($str)
    {
        $days = [
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
            7 => 'Sun'
        ];
        $results = Regex::matchAll('/([A-Z][a-z][a-z])/', $str)->results();
        switch (count($results)) {
            case '1':
                return [array_search($results[0]->group(1),$days),array_search($results[0]->group(1),$days)];
                break;
            
            default:
                return [array_search($results[0]->group(1),$days),array_search($results[1]->group(1),$days)];
                break;
        }
    }

    public function dayWeekValue($str)
    {
        $results = Regex::matchAll('/([0-9][0-9]:[0-9][0-9]|[0-9]:[0-9][0-9]).(am|pm)/', $str)->results();
        if (empty($results)) {
            return;
        }
        return [
            'start' => ($results[0]->group(2) === 'am') ? date("H:i",strtotime($results[0]->group(1))) :  date("H:i",strtotime("+12 hour",strtotime($results[0]->group(1)))),
            'end' => ($results[1]->group(2) === 'am') ? date("H:i",strtotime($results[1]->group(1))) :  date("H:i",strtotime("+12 hour",strtotime($results[1]->group(1)))),
        ];
    }

    public function getHours($code)
    {
        $out = [];
        foreach ($code as $item) {
            $crawler = new Crawler($item);
            $title = $crawler->filter('span.day-text')->each(function (Crawler $node, $i) {
                    return $node->text();
            });

            $day_label = $crawler->filter('span.day-label')->each(function (Crawler $node, $i) {
                    return $node->text();
            });
            $day_hours = $crawler->filter('span.day-hours')->each(function (Crawler $node, $i) {
                    return $node->text();
            });
            $out[$title[0]] = [
                'day_label' => $day_label,
                'day_hours' => $day_hours,
            ];
        }
        
        return [
            'regular' => (empty($out['Regular Hours']) === false) ? $this->formatHours($out['Regular Hours']) : null,
            'delivery' => (empty($out['Delivery Hours']) === false) ? $this->formatHours($out['Delivery Hours']) : null,
        ];                
    }

    public function formatHours($hours)
    {
        $out = [];
        foreach ($hours['day_label'] as $n => $value) {
            $out[$value] = ArrayHelper::getValue($hours['day_hours'],$n);
        }
        $data = [];
        foreach ($out as $day => $value) {
            $dayWeek = $this->dayWeek($day);
            $dayWeekValue = $this->dayWeekValue($value);
            $currentDay = $dayWeek[0];
            while ($currentDay <= $dayWeek[1]) {
                $data[$currentDay] = $dayWeekValue;
                $currentDay++;
            }        
        }
        return $data;
    }

    public function paymentMethod($str)
    {
        return explode(', ', $str);
    }

    public function address($array)
    {
        return implode($array);
    }

    public function ypid($url)
    {
        $query = ArrayHelper::getValue(parse_url($url),'query');
        parse_str($query,$lid);
        return ArrayHelper::getValue($lid,'lid');
    }
    
}
