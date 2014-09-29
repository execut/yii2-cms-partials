<?php

use yii\helpers\Html;
use infoweb\cms\CMSAsset;

// Register assets
CMSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Page Partial',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Page Partials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-partial-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>