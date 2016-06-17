<?php
use yii\helpers\Html;

$properties = [];
if(!Yii::$app->user->can('Superadmin')) {
    $properties['disabled'] = 'disabled';
}

?>
<div class="tab-content default-tab">

    <?= $form->field($model, "name")->textInput(); ?>

    <?php echo $form->field($model, 'type')->dropDownList([
        'system'        => Yii::t('app', 'System'),
        'user-defined'  => Yii::t('app', 'User defined')
    ], $properties); 

    ?>
    <div hidden="hidden">    
        <?php echo $form->field($model, 'type')->hiddenInput()->label(''); ?>
    </div>
</div>