<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataDraft */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="port-call-data-draft-form form-inline">

    <?php $form = ActiveForm::begin(/* ['action' => '/emperor/backend/web/appointment/port-call-data/update-draft-rob/?id=' . $model_draft->appointment_id] */); ?>

    <?php //$form->field($model, 'appointment_id')->textInput(['readonly' => true, 'value' => $model->appointment->appointment_no]) ?>

    <?php //$form->field($model, 'data_id')->textInput() ?>
    <div class="form-group "><h4 class="portcall-rob"><b><u>SURVEY TIMINGS</u></b></h4></div>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "><h4 class="portcall-rob">DRAFT- ARRIVAL</h4></div>
    <div class="form-group "><h4 class="portcall-rob">DRAFT- SAILING</h4></div>
    <?= $form->field($model_draft, 'intial_survey_commenced')->textInput(['tabindex' => 1]) ?>

    <?= $form->field($model_draft, 'finial_survey_commenced')->textInput(['tabindex' => 3]) ?>
    <div class="form-group "></div>

    <?= $form->field($model_draft, 'fwd_arrival_quantity')->textInput(['tabindex' => 5, 'class' => 'decimaldraft form-control']) ?>

    <?= $form->field($model_draft, 'fwd_sailing_quantity')->textInput(['tabindex' => 8, 'class' => 'decimaldraft form-control']) ?>

    <?= $form->field($model_draft, 'intial_survey_completed')->textInput(['tabindex' => 2]) ?>

    <?= $form->field($model_draft, 'finial_survey_completed')->textInput(['tabindex' => 4]) ?>
    <div class="form-group "></div>

    <?= $form->field($model_draft, 'aft_arrival_quantity')->textInput(['tabindex' => 6, 'class' => 'decimaldraft form-control']) ?>

    <?= $form->field($model_draft, 'aft_sailing_quantity')->textInput(['tabindex' => 9, 'class' => 'decimaldraft form-control']) ?>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "></div>

    <?= $form->field($model_draft, 'mean_arrival_quantity')->textInput(['tabindex' => 7, 'class' => 'decimaldraft form-control']) ?>

    <?= $form->field($model_draft, 'mean_sailing_quantity')->textInput(['tabindex' => 10, 'class' => 'decimaldraft form-control']) ?>



    <div class="form-group "><h4 class="portcall-rob">ROB- ARRIVAL</h4></div>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "><h4 class="portcall-rob">ROB- SAILING</h4></div>
    <div class="form-group "></div>

    <?php $arr = array('1' => 'Metric Ton', '2' => 'Liter'); ?>

    <?= $form->field($model_rob, 'fo_arrival_unit')->dropDownList($arr, ['prompt' => '-choose arrival unit-', 'tabindex' => 11]) ?>

    <?= $form->field($model_rob, 'fo_arrival_quantity')->textInput(['tabindex' => 12, 'class' => 'decimal form-control']) ?>

    <div class="form-group "></div>

    <?= $form->field($model_rob, 'fo_sailing_unit')->dropDownList($arr, ['prompt' => '-choose sailing unit-', 'tabindex' => 21]) ?>

    <?= $form->field($model_rob, 'fo_sailing_quantity')->textInput(['tabindex' => 22, 'class' => 'decimal form-control']) ?>


    <?= $form->field($model_rob, 'do_arrival_unit')->dropDownList($arr, ['prompt' => '-choose arrival unit-', 'tabindex' => 13]) ?>

    <?= $form->field($model_rob, 'do_arrival_quantity')->textInput(['tabindex' => 14, 'class' => 'decimal form-control']) ?>

    <div class="form-group "></div>

    <?= $form->field($model_rob, 'do_sailing_unit')->dropDownList($arr, ['prompt' => '-choose sailing unit-', 'tabindex' => 23]) ?>

    <?= $form->field($model_rob, 'do_sailing_quantity')->textInput(['tabindex' => 24, 'class' => 'decimal form-control']) ?>

    <?= $form->field($model_rob, 'go_arrival_unit')->dropDownList($arr, ['prompt' => '-choose arrival unit-', 'tabindex' => 15]) ?>

    <?= $form->field($model_rob, 'go_arrival_quantity')->textInput(['tabindex' => 16, 'class' => 'decimal form-control']) ?>

    <div class="form-group "></div>

    <?= $form->field($model_rob, 'go_sailing_unit')->dropDownList($arr, ['prompt' => '-choose sailing unit-', 'tabindex' => 25]) ?>

    <?= $form->field($model_rob, 'go_sailing_quantity')->textInput(['tabindex' => 26, 'class' => 'decimal form-control']) ?>

    <?= $form->field($model_rob, 'lo_arrival_unit')->dropDownList($arr, ['prompt' => '-choose arrival unit-', 'tabindex' => 17]) ?>

    <?= $form->field($model_rob, 'lo_arrival_quantity')->textInput(['tabindex' => 18, 'class' => 'decimal form-control']) ?>

    <div class="form-group "></div>

    <?= $form->field($model_rob, 'lo_sailing_unit')->dropDownList($arr, ['prompt' => '-choose sailing unit-', 'tabindex' => 27]) ?>

    <?= $form->field($model_rob, 'lo_sailing_quantity')->textInput(['tabindex' => 28, 'class' => 'decimal form-control']) ?>

    <?= $form->field($model_rob, 'fresh_water_arrival_unit')->dropDownList($arr, ['prompt' => '-choose arrival unit-', 'tabindex' => 19]) ?>

    <?= $form->field($model_rob, 'fresh_water_arrival_quantity')->textInput(['tabindex' => 20, 'class' => 'decimal form-control']) ?>

    <div class="form-group "></div>
    <?= $form->field($model_rob, 'fresh_water_sailing_unit')->dropDownList($arr, ['prompt' => '-choose sailing unit-', 'tabindex' => 29]) ?>

    <?= $form->field($model_rob, 'fresh_water_sailing_quantity')->textInput(['tabindex' => 30, 'class' => 'decimal form-control']) ?>

    <?= $form->field($model_rob, 'rob_received')->textInput(['tabindex' => 31]) ?>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "></div>


    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <style>
        .btstyle{
            margin-left: 431px;
        }
        .portcall-rob{
            color:#0e62c7;
            font-size: 15px;
            margin-left: 31px;
            margin-bottom: 12px;
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
    <script>
            $(document).ready(function () {
                /*
                 * To add decimal(.000) to the desired text fields
                 */
                $('.decimal').blur(function () {
                    var str = $(this).val();
                    if (str != '') {
                        if (str.indexOf('.') === -1) {
                            $(this).val(str + '.000');

                        }
                        else {
                            var substr = str.split('.');
                            var len = substr[1].length;
                            if (len == 1) {
                                $(this).val(str + '00');
                            }
                            else if (len == 2) {
                                $(this).val(str + '0');
                            } else if (len == 0) {
                                $(this).val(str + '000');
                            }


                        }
                    }

                });


                $('.decimaldraft').blur(function () {
                    var str = $(this).val();
                    if (str != '') {
                        if (str.indexOf('.') === -1) {
                            $(this).val(str + '.00');

                        }
                        else {
                            var substr = str.split('.');
                            var len = substr[1].length;
                            if (len == 1) {
                                $(this).val(str + '0');
                            }
                            else if (len == 0) {
                                $(this).val(str + '00');
                            }


                        }
                    }
                });



            });
    </script>
</div>
