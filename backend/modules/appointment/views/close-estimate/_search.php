<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CloseEstimateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="close-estimate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'apponitment_id') ?>

    <?= $form->field($model, 'service_id') ?>

    <?= $form->field($model, 'supplier') ?>

    <?= $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'unit_rate') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'roe') ?>

    <?php // echo $form->field($model, 'epda') ?>

    <?php // echo $form->field($model, 'fda') ?>

    <?php // echo $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'invoice_type') ?>

    <?php // echo $form->field($model, 'principal') ?>

    <?php // echo $form->field($model, 'comments') ?>

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
