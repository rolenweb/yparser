<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class TooltipRolenweb extends Widget
{

    public $title;
    public $buttonTitle;
    public $class;
    public $animation;
    public $container;
    public $delay;
    public $html;
    public $placement;
    public $selector;
    public $template;
    public $trigger;
    public $options;

    public function init()
    {
        parent::init();
        if ($this->title === null) {
            $this->title = 'Title Here';
        }
        if ($this->buttonTitle === null) {
            $this->buttonTitle = 'Tooltip';
        }
        if ($this->class === null) {
            $this->class = 'btn btn-default';
        }
        if ($this->animation === null) {
            $this->animation = 'true';
        }
        if ($this->container === null) {
            $this->container = 'false';
        }
        if ($this->delay === null) {
            $this->delay = '0';
        }
        if ($this->html === null) {
            $this->html = 'false';
        }
        if ($this->placement === null) {
            $this->placement = 'top';
        }
        if ($this->selector === null) {
            $this->selector = 'false';
        }
        
        if ($this->template === null) {
            $this->template = '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>';
        }
        if ($this->trigger === null) {
            $this->trigger = 'hover focus';
        }

        
    }

    public function run()
    {   
        $buttonOption = [
                'title' => $this->title,
                'class' => $this->class,
                'data-toggle' => 'tooltip',
                'data-animation' => $this->animation,
                'data-container' => $this->container,
                'data-delay' => $this->delay,
                'data-html' => $this->html,
                'data-placement' => $this->placement,
                'data-selector' => $this->selector,
                'data-template' => $this->template,
                'data-trigger' => $this->trigger
            ];

        $buttonOption = ArrayHelper::merge($buttonOption,$this->options);
        
        return Html::button($this->buttonTitle,$buttonOption);
    }
}
?>