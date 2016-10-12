<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppointmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appointment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'vessel_type') ?>

    <?= $form->field($model, 'vessel') ?>

    <?= $form->field($model, 'port_of_call') ?>

    <?= $form->field($model, 'terminal') ?>

    <?php // echo $form->field($model, 'birth_no') ?>

    <?php // echo $form->field($model, 'appointment_no') ?>

    <?php // echo $form->field($model, 'no_of_principal') ?>

    <?php // echo $form->field($model, 'principal') ?>

    <?php // echo $form->field($model, 'nominator') ?>

    <?php // echo $form->field($model, 'charterer') ?>

    <?php // echo $form->field($model, 'shipper') ?>

    <?php // echo $form->field($model, 'purpose') ?>

    <?php // echo $form->field($model, 'cargo') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'last_port') ?>

    <?php // echo $form->field($model, 'next_port') ?>

    <?php // echo $form->field($model, 'eta') ?>

    <?php // echo $form->field($model, 'stage') ?>

    <?php // echo $form->field($model, 'status') ?>

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
