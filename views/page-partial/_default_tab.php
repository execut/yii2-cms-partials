<?php
use yii\helpers\Html;
?>
<div class="tab-content default-tab">
    <?= $form->field($model, 'type')->dropDownList([
        'system'        => Yii::t('app', 'System'),
        'user-defined'  => Yii::t('app', 'User defined')
    ]); ?>
</div>