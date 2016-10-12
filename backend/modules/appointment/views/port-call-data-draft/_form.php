<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataDraft */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-call-data-draft-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'appointment_id')->textInput() ?>

    <?= $form->field($model, 'data_id')->textInput() ?>

    <?= $form->field($model, 'intial_survey_commenced')->textInput() ?>

    <?= $form->field($model, 'intial_survey_completed')->textInput() ?>

    <?= $form->field($model, 'finial_survey_commenced')->textInput() ?>

    <?= $form->field($model, 'finial_survey_completed')->textInput() ?>

<!--    <?echo  $form->field($model, 'fwd_arrival_unit')->textInput() ?>-->

    <?= $form->field($model, 'fwd_arrival_quantity')->textInput() ?>

<!--    <?echo $form->field($model, 'aft_arrival_unit')->textInput() ?>-->

    <?= $form->field($model, 'aft_arrival_quantity')->textInput() ?>

<!--    <?echo $form->field($model, 'mean_arrival_unit')->textInput() ?>-->

    <?= $form->field($model, 'mean_arrival_quantity')->textInput() ?>

    <!--<?echo $form->field($model, 'fwd_sailing_unit')->textInput() ?>-->

    <?= $form->field($model, 'fwd_sailing_quantity')->textInput() ?>

    <!--<?echo $form->field($model, 'aft_sailing_unit')->textInput() ?>-->

    <?= $form->field($model, 'aft_sailing_quantity')->textInput() ?>

    <!--<?echo $form->field($model, 'mean_sailing_unit')->textInput() ?>-->

    <?= $form->field($model, 'mean_sailing_quantity')->textInput() ?>

    <?= $form->field($model, 'additional_info')->textInput() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

   <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>


    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
