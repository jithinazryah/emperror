<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ImigrationClearanceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imigration-clearance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'appointment_id') ?>

    <?= $form->field($model, 'arrived_ps') ?>

    <?= $form->field($model, 'pob_inbound') ?>

    <?= $form->field($model, 'first_line_ashore') ?>

    <?php // echo $form->field($model, 'all_fast') ?>

    <?php // echo $form->field($model, 'agent_on_board') ?>

    <?php // echo $form->field($model, 'imi_clearence_commenced') ?>

    <?php // echo $form->field($model, 'imi_clearence_completed') ?>

    <?php // echo $form->field($model, 'pob_outbound') ?>

    <?php // echo $form->field($model, 'cast_off') ?>

    <?php // echo $form->field($model, 'last_line_away') ?>

    <?php // echo $form->field($model, 'cleared_break_water') ?>

    <?php // echo $form->field($model, 'drop_anchor') ?>

    <?php // echo $form->field($model, 'heave_up_anchor') ?>

    <?php // echo $form->field($model, 'pilot_boarded') ?>

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
