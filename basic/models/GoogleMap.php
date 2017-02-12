<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\Model;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;

use Ivory\GoogleMap\Helper\MapHelper;
use Ivory\GoogleMap\Helper\ApiHelper;

use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\InfoWindow;

use Ivory\GoogleMap\Events\MouseEvent;
/**
 * ContactForm is the model behind the contact form.
 */
class GoogleMap extends Model
{
    private $key;


    public function __construct($key)
    {
        $this->key = $key;   
    }

    public function createMap($lat,$lon)
    {
        $map = new Map();
        $map->setPrefixJavascriptVariable('map_');
        $map->setHtmlContainerId('map_canvas');
        $map->setAsync(false);
        $map->setAutoZoom(false);
        $map->setCenter($lat, $lon, true);
        $map->setMapOption('zoom', 14);
        //$map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);
        $map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
        $map->setMapOption('mapTypeId', 'roadmap');
        $map->setMapOptions(
            [
                'disableDefaultUI'       => true,
                'disableDoubleClickZoom' => true,
            ]
            
        );
        $map->setStylesheetOptions(
            [
                'width'  => '100%',
                'height' => '600px',
            ]
        );
        $map->setLanguage('en');
        return $map;
    }

    public function createMarker($lat,$lon)
    {
        $marker = new Marker();
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($lat, $lon, true);
        $marker->setAnimation(Animation::DROP);

        $marker->setOptions(array(
            //'clickable' => false,
            'flat'      => true,
        ));
        return $marker;
    }

    public function createInfoWindow($lat,$lon,$content)
    {
        $infoWindow = new InfoWindow();

        // Configure your info window options
        $infoWindow->setPrefixJavascriptVariable('info_window_');
        //$infoWindow->setPosition($lat, $lon, true);
        //$infoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt');
        $infoWindow->setContent($content);
        $infoWindow->setOpen(false);
        $infoWindow->setAutoOpen(true);
        $infoWindow->setOpenEvent(MouseEvent::CLICK);
        $infoWindow->setAutoClose(false);
        $infoWindow->setOptions(
            [
                'disableAutoPan' => true,
                'zIndex'         => 10,
            ]
        );

        return $infoWindow;
    }

    public function render($map)
    {
        $apiHelper = new ApiHelper();
        $mapHelper = new MapHelper();
        
        return $apiHelper->render('en',[],null,false,$this->key).$mapHelper->renderHtmlContainer($map).$mapHelper->renderJavascripts($map);
    }
}
