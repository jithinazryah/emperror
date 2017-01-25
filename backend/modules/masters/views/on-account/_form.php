<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OnAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="on-account-form form-inline">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'date')->textInput() ?>

        <?= $form->field($model, 'payment_type')->dropDownList(['1' => 'Cash', '2' => 'Check'], ['prompt' => '-Payment Type-']) ?>

        <?= $form->field($model, 'check_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'amount')->textInput() ?>

        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '2' => 'Disabled'], ['prompt' => '-Status-']) ?>


        <div class="form-group" style="float: right;">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <script>
                $(document).ready(function () {
                        $("#onaccount-check_no").prop("disabled", true);
                        $('#onaccount-payment_type').change(function () {
                                var payment_id = $(this).val();
                                if (payment_id == 2) {
                                        $("#onaccount-check_no").prop("disabled", false);
                                } else {
                                        $("#onaccount-check_no").prop("disabled", true);
                                }
                        });
                });
        </script>

</div>
