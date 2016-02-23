<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-partial-form">

    <?php // Flash messages ?>
    <?php echo $this->render('_flash_messages'); ?>

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'page-partial-form',
        'options'                   => ['class' => 'tabbed-form', 'enctype' => 'multipart/form-data'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false        
    ]);

    // Initialize the tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', [
                'model' => $model,
                'form' => $form,
                'allowContentDuplication' => $allowContentDuplication,
            ]),
        ],
        [
            'label'     => Yii::t('app', 'Data'),
            'content'   => $this->render('_data_tab', ['model' => $model, 'form' => $form]),
        ],
        [
            'label' => Yii::t('infoweb/cms', 'Image'),
            'content' => $this->render('_image_tab', ['model' => $model, 'form' => $form]),
        ],
    ]; 
    
    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>

    <div class="form-group buttons">
        <?= $this->render('@infoweb/cms/views/ui/formButtons', ['model' => $model]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>