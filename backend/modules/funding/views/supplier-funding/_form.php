<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SupplierFunding */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-funding-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'appointment_id')->textInput() ?>

    <?= $form->field($model, 'close_estimate_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'supplier')->textInput() ?>

    <?= $form->field($model, 'actual_amount')->textInput() ?>

    <?= $form->field($model, 'amount_debit')->textInput() ?>

    <?= $form->field($model, 'balance_amount')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'CB')->textInput() ?>

    <?= $form->field($model, 'UB')->textInput() ?>

    <?= $form->field($model, 'DOC')->textInput() ?>

    <?= $form->field($model, 'DOU')->textInput() ?>

    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
