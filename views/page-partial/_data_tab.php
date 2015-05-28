<?php
use yii\helpers\Html;
?>
<div class="tab-content default-tab">

    <?= $form->field($model, "name")->textInput(); ?>

    <?= $form->field($model, 'type')->dropDownList([
        'system'        => Yii::t('app', 'System'),
        'user-defined'  => Yii::t('app', 'User defined')
    ],[
        'options' => [
            'system' => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true]
        ]
    ]); ?>
</div>