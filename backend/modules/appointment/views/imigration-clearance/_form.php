<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ImigrationClearance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="imigration-clearance-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'appointment_id')->textInput() ?>

    <?= $form->field($model, 'arrived_ps')->textInput() ?>

    <?= $form->field($model, 'pob_inbound')->textInput() ?>

    <?= $form->field($model, 'first_line_ashore')->textInput() ?>

    <?= $form->field($model, 'all_fast')->textInput() ?>

    <?= $form->field($model, 'agent_on_board')->textInput() ?>

    <?= $form->field($model, 'imi_clearence_commenced')->textInput() ?>

    <?= $form->field($model, 'imi_clearence_completed')->textInput() ?>

    <?= $form->field($model, 'pob_outbound')->textInput() ?>

    <?= $form->field($model, 'cast_off')->textInput() ?>

    <?= $form->field($model, 'last_line_away')->textInput() ?>

    <?= $form->field($model, 'cleared_break_water')->textInput() ?>

    <?= $form->field($model, 'drop_anchor')->textInput() ?>

    <?= $form->field($model, 'heave_up_anchor')->textInput() ?>

    <?= $form->field($model, 'pilot_boarded')->textInput() ?>

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
