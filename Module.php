<?php

namespace infoweb\partials;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'infoweb\partials\controllers';

    /**
     * Module specific configuration of the ckEditor
     * @var array
     */
    public $ckEditorOptions = [
        'height' => 500
    ];
    
    public function init()
    {
        parent::init();
        
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}
