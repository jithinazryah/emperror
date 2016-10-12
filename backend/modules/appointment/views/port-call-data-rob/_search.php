<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataRobSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-call-data-rob-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'appointment_id') ?>

    <?= $form->field($model, 'fo_arrival_unit') ?>

    <?= $form->field($model, 'fo_arrival_quantity') ?>

    <?= $form->field($model, 'do_arrival_unit') ?>

    <?php // echo $form->field($model, 'do_arrival_quantity') ?>

    <?php // echo $form->field($model, 'go_arrival_unit') ?>

    <?php // echo $form->field($model, 'go_arrival_quantity') ?>

    <?php // echo $form->field($model, 'lo_arrival_unit') ?>

    <?php // echo $form->field($model, 'lo_arrival_quantity') ?>

    <?php // echo $form->field($model, 'fresh_water_arrival_unit') ?>

    <?php // echo $form->field($model, 'fresh_water_arrival_quantity') ?>

    <?php // echo $form->field($model, 'fo_sailing_unit') ?>

    <?php // echo $form->field($model, 'fo_sailing_quantity') ?>

    <?php // echo $form->field($model, 'do_sailing_unit') ?>

    <?php // echo $form->field($model, 'do_sailing_quantity') ?>

    <?php // echo $form->field($model, 'go_sailing_unit') ?>

    <?php // echo $form->field($model, 'go_sailing_quantity') ?>

    <?php // echo $form->field($model, 'lo_sailing_unit') ?>

    <?php // echo $form->field($model, 'lo_sailing_quantity') ?>

    <?php // echo $form->field($model, 'fresh_water_sailing_unit') ?>

    <?php // echo $form->field($model, 'fresh_water_sailing_quantity') ?>

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
