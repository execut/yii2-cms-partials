<?php
use mihaildev\ckeditor\CKEditor;
?>
<div class="tab-content language-tab">
    <?= $form->field($model, "[{$model->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "PagePartialLang[{$model->language}][title]"
    ]); ?>
    
    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'name' => "PagePartialLang[{$model->language}][content]",
        'editorOptions' => Yii::$app->getModule('cms')->getCKEditorOptions(),        
    ]); ?>
</div>