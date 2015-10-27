<?php

namespace infoweb\partials;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'infoweb\partials\controllers';

    /**
     * Allow content duplication with the "duplicateable" plugin
     * @var boolean
     */
    public $allowContentDuplication = true;

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
        Yii::configure($this, require(__DIR__ . '/config.php'));

        // Content duplication is only possible if there is more than 1 app language
        if (isset(Yii::$app->params['languages']) && count(Yii::$app->params['languages']) == 1) {
            $this->allowContentDuplication = false;
        }
    }
}
