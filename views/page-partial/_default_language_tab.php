<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use infoweb\cms\helpers\LanguageHelper;
?>
<div class="tab-content language-tab">
    <?= $form->field($model, "[{$model->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "PagePartialLang[{$model->language}][title]",
        'data-duplicateable' => Yii::$app->getModule('partials')->allowContentDuplication ? 'true' : 'false',
    ]); ?>
    
    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'name' => "PagePartialLang[{$model->language}][content]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), Yii::$app->getModule('partials')->ckEditorOptions, (LanguageHelper::isRtl($model->language)) ? ['contentsLangDirection' => 'rtl'] : []),
        'options' => ['data-duplicateable' => Yii::$app->getModule('partials')->allowContentDuplication ? 'true' : 'false'],
    ]); ?>
</div>