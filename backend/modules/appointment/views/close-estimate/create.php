<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CloseEstimate */

$this->title = 'Create Close Estimate';
$this->params['breadcrumbs'][] = ['label' => 'Close Estimates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="close-estimate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
