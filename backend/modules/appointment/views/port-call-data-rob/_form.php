<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataRob */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-call-data-rob-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'appointment_id')->textInput() ?>

    <?= $form->field($model, 'fo_arrival_unit')->textInput() ?>

    <?= $form->field($model, 'fo_arrival_quantity')->textInput() ?>

    <?= $form->field($model, 'do_arrival_unit')->textInput() ?>

    <?= $form->field($model, 'do_arrival_quantity')->textInput() ?>

    <?= $form->field($model, 'go_arrival_unit')->textInput() ?>

    <?= $form->field($model, 'go_arrival_quantity')->textInput() ?>

    <?= $form->field($model, 'lo_arrival_unit')->textInput() ?>

    <?= $form->field($model, 'lo_arrival_quantity')->textInput() ?>

    <?= $form->field($model, 'fresh_water_arrival_unit')->textInput() ?>

    <?= $form->field($model, 'fresh_water_arrival_quantity')->textInput() ?>

    <?= $form->field($model, 'fo_sailing_unit')->textInput() ?>

    <?= $form->field($model, 'fo_sailing_quantity')->textInput() ?>

    <?= $form->field($model, 'do_sailing_unit')->textInput() ?>

    <?= $form->field($model, 'do_sailing_quantity')->textInput() ?>

    <?= $form->field($model, 'go_sailing_unit')->textInput() ?>

    <?= $form->field($model, 'go_sailing_quantity')->textInput() ?>

    <?= $form->field($model, 'lo_sailing_unit')->textInput() ?>

    <?= $form->field($model, 'lo_sailing_quantity')->textInput() ?>

    <?= $form->field($model, 'fresh_water_sailing_unit')->textInput() ?>

    <?= $form->field($model, 'fresh_water_sailing_quantity')->textInput() ?>

    <?= $form->field($model, 'additional_info')->textInput() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>


    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
