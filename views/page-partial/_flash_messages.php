<?php if (Yii::$app->getSession()->hasFlash('partial')): ?>
<div class="alert alert-success">
    <p><?= Yii::$app->getSession()->getFlash('partial') ?></p>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('partial-error')): ?>
<div class="alert alert-danger">
    <p><?= Yii::$app->getSession()->getFlash('partial-error') ?></p>
</div>
<?php endif; ?>