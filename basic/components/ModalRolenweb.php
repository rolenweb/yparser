<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ModalRolenweb extends Widget
{
    public $btnTitle;
    public $btnOptions;
    public $modalOptions;

    public function init()
    {
        parent::init();
        if ($this->btnTitle === null) {
            $this->btnTitle = 'Modal';
        }
        if (empty($this->btnOptions)) {
            $this->btnOptions = [
                'class' => 'btn btn-primary',
                'data-target' => '#modal',
                
            ];
        }
        

        
    }

    public function run()
    {   
        
        $this->btnOptions = ArrayHelper::merge($this->btnOptions,['data-toggle' => 'modal']);

        return $this->render('modal_rolenweb',
                [
                    'btnTitle' => $this->btnTitle,
                    'btnOptions' => $this->btnOptions,
                    'modalOptions' => $this->modalOptions
                ]
            );
    }
}
?>