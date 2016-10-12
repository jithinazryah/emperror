<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCargoDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-cargo-details-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model_port_cargo_details, 'cargo_type')->textarea(['rows' => 6]) ?>

    <?= $form->field($model_port_cargo_details, 'loaded_quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_port_cargo_details, 'bl_quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_port_cargo_details, 'remarks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model_port_cargo_details, 'stoppages_delays')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_port_cargo_details, 'cargo_document')->textarea(['rows' => 6]) ?>

    <?= $form->field($model_port_cargo_details, 'masters_comment')->textarea(['rows' => 6]) ?>


    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
