<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('infoweb/partials', 'Page Partial'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/partials', 'Page Partials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-partial-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'                   => $model,
        'allowContentDuplication' => $allowContentDuplication,
    ]) ?>

</div>