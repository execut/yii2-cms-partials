<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-partial-form">

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'page-partial-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        /*'afterValidateAll'  => 'function($form, jqXHR, textStatus) {
            // The first tab that contains errors has to be shown
            if ($(".has-error").length) {
                $("a[href=#"+$(".has-error").parents(".tab-pane").attr("id")+"]").tab("show");    
            }
        }'*/        
    ]);

    // Initialize the tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', ['model' => $model, 'form' => $form]),
        ]
    ];
    
    // Add the language tabs
    foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
        $tabs[] = [
            'label' => $languageName,
            'content' => $this->render('_language_tab', ['model' => $model->getTranslation($languageId), 'form' => $form]),
            'active' => ($languageId == Yii::$app->language) ? true : false
        ];
    } 
    
    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);   
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>