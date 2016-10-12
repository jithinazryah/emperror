<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CloseEstimate */

$this->title = 'Update Close Estimate: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Close Estimates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="close-estimate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
