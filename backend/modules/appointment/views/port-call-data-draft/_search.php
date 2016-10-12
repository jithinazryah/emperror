<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataDraftSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-call-data-draft-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'appointment_id') ?>

    <?= $form->field($model, 'data_id') ?>

    <?= $form->field($model, 'intial_survey_commenced') ?>

    <?= $form->field($model, 'intial_survey_completed') ?>

    <?php // echo $form->field($model, 'finial_survey_commenced') ?>

    <?php // echo $form->field($model, 'finial_survey_completed') ?>

    <?php // echo $form->field($model, 'fwd_arrival_unit') ?>

    <?php // echo $form->field($model, 'fwd_arrival_quantity') ?>

    <?php // echo $form->field($model, 'aft_arrival_unit') ?>

    <?php // echo $form->field($model, 'aft_arrival_quantity') ?>

    <?php // echo $form->field($model, 'mean_arrival_unit') ?>

    <?php // echo $form->field($model, 'mean_arrival_quantity') ?>

    <?php // echo $form->field($model, 'fwd_sailing_unit') ?>

    <?php // echo $form->field($model, 'fwd_sailing_quantity') ?>

    <?php // echo $form->field($model, 'aft_sailing_unit') ?>

    <?php // echo $form->field($model, 'aft_sailing_quantity') ?>

    <?php // echo $form->field($model, 'mean_sailing_unit') ?>

    <?php // echo $form->field($model, 'mean_sailing_quantity') ?>

    <?php // echo $form->field($model, 'additional_info') ?>

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
