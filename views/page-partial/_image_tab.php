<?php
use yii\helpers\Html;
use kartik\widgets\FileInput;

$initialPreview = [];
$initialCaption = '';

if (strlen($model->getImage()->name) > 0) {
    $initialPreview = [
        Html::img($model->getImage()->getUrl(), ['class' => 'file-preview-image', 'alt' => $model->getImage()->alt, 'title' => $model->getImage()->alt]),
    ];

    $initialCaption = $model->getImage()->name;
}

?>
<div class="tab-content image-tab">

    <?= FileInput::widget([
        'name' => 'ImageUploadForm[image]',
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'initialPreview' => $initialPreview,
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
            'initialCaption' => $initialCaption,
            'browseLabel' => Yii::t('app', 'Browse'),
            'removeLabel' => Yii::t('app', 'Remove'),
            'removeTitle' => Yii::t('app', 'Remove selected files'),
            'uploadLabel' => Yii::t('app', 'Upload'),
            'uploadTitle' => Yii::t('app', 'Upload selected files'),
            'cancelLabel' => Yii::t('app', 'Cancel'),
        ],
        'pluginEvents' => [
            "fileclear" => "function() {
                var request = $.post('remove-images', {model: '{$model->id}'});
                request.done(function(response) {
                });
            }"
        ]
    ]) ?>

</div>