<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCargoDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-cargo-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'appointment_id') ?>

    <?= $form->field($model, 'port_call_id') ?>

    <?= $form->field($model, 'cargo_type') ?>

    <?= $form->field($model, 'loaded_quantity') ?>

    <?php // echo $form->field($model, 'bl_quantity') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'stoppages_delays') ?>

    <?php // echo $form->field($model, 'cargo_document') ?>

    <?php // echo $form->field($model, 'masters_comment') ?>

    <?php // echo $form->field($model, 'CB') ?>

    <?php // echo $form->field($model, 'UB') ?>

    <?php // echo $form->field($model, 'DOC') ?>

    <?php // echo $form->field($model, 'DOU') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
