<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SupplierFundingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-funding-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'appointment_id') ?>

    <?= $form->field($model, 'close_estimate_id') ?>

    <?= $form->field($model, 'service_id') ?>

    <?= $form->field($model, 'supplier') ?>

    <?php // echo $form->field($model, 'actual_amount') ?>

    <?php // echo $form->field($model, 'amount_debit') ?>

    <?php // echo $form->field($model, 'balance_amount') ?>

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
