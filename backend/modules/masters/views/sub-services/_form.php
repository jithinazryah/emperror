<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Services;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\SubServices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-services-form form-inline">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'service_id')->dropDownList(ArrayHelper::map(Services::findAll(['status' => 1]), 'id', 'service'), ['prompt' => '-Service-']) ?>

    <?= $form->field($model, 'sub_service')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit')->textInput() ?>

    <?= $form->field($model, 'unit_price')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>



    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <script>
            $(document).ready(function () {
                $("#subservices-unit").keyup(function () {
                    multiply();
                });
                $("#subservices-unit_price").keyup(function () {
                    multiply();
                });
            });
            function multiply() {
                var unit = $("#subservices-unit").val();
                var rate = $("#subservices-unit_price").val();
                if (unit != '' && rate != '') {
                    $("#subservices-total").val(rate * unit);
                }

            }
            $( "#subservices-total" ).prop( "disabled", true );  
    </script>
</div>
