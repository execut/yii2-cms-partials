<?php
namespace infoweb\partials;

use yii\web\AssetBundle as AssetBundle;

class PartialsAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/partials/assets/';
    public $js = [];
    public $css = [];
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}