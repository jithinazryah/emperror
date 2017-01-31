<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GenerateInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="generate-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'invoice') ?>

    <?= $form->field($model, 'to_address') ?>

    <?= $form->field($model, 'invoice_number') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'oops_id') ?>

    <?php // echo $form->field($model, 'on_account_of') ?>

    <?php // echo $form->field($model, 'job') ?>

    <?php // echo $form->field($model, 'payment_terms') ?>

    <?php // echo $form->field($model, 'doc_no') ?>

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
