<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class PopoverRolenweb extends Widget
{

    public $id;
    public $title;
    public $buttonTitle;
    public $class;
    public $container;
    public $placement;
    public $content;
    public $html;
    public $template;
    public $options;

    public function init()
    {
        parent::init();
        if ($this->title === null) {
            $this->title = 'Title Popover';
        }
        if ($this->class === null) {
            $this->class = 'btn btn-default';
        }
        if ($this->container === null) {
            $this->container = 'body';
        }
        if ($this->placement === null) {
            $this->placement = 'right';
        }
        if ($this->html === null) {
            $this->html = 'true';
        }
        if ($this->template === null) {
            $this->template = '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>';
        }

        
    }

    public function run()
    {   
        $buttonOption = [
                'id' => $this->id,
                'popover-id' => 'popover-rolenweb',
                'title' => $this->title.'<button type="button" class="close" data-dismiss="popover-rolenweb" aria-hidden="true">×</button>',
                'class' => $this->class,
                'data-toggle' => 'popover',
                'data-container' => $this->container,
                'data-placement' => $this->placement,
                'data-content' => $this->content,
                'data-html' => $this->html,
                'data-template' => $this->template

            ];

        $buttonOption = ArrayHelper::merge($buttonOption,$this->options);
        
        return Html::button($this->buttonTitle,$buttonOption);
    }
}
?>