<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-call-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'appointment_id') ?>

    <?= $form->field($model, 'eta') ?>

    <?= $form->field($model, 'ets') ?>

    <?= $form->field($model, 'eosp') ?>

    <?php // echo $form->field($model, 'arrived_anchorage') ?>

    <?php // echo $form->field($model, 'nor_tendered') ?>

    <?php // echo $form->field($model, 'dropped_anchor') ?>

    <?php // echo $form->field($model, 'anchor_aweigh') ?>

    <?php // echo $form->field($model, 'arrived_pilot_station') ?>

    <?php // echo $form->field($model, 'pob_inbound') ?>

    <?php // echo $form->field($model, 'first_line_ashore') ?>

    <?php // echo $form->field($model, 'all_fast') ?>

    <?php // echo $form->field($model, 'gangway_down') ?>

    <?php // echo $form->field($model, 'agent_on_board') ?>

    <?php // echo $form->field($model, 'immigration_commenced') ?>

    <?php // echo $form->field($model, 'immigartion_completed') ?>

    <?php // echo $form->field($model, 'cargo_commenced') ?>

    <?php // echo $form->field($model, 'cargo_completed') ?>

    <?php // echo $form->field($model, 'pob_outbound') ?>

    <?php // echo $form->field($model, 'lastline_away') ?>

    <?php // echo $form->field($model, 'cleared_channel') ?>

    <?php // echo $form->field($model, 'cosp') ?>

    <?php // echo $form->field($model, 'fasop') ?>

    <?php // echo $form->field($model, 'eta_next_port') ?>

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
