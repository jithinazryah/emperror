<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallData */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="port-call-data-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'appointment_id')->textInput(['readonly' => true, 'value' => $model->appointment->appointment_no]) ?>
    
    <div class="form-group "><h4 class="portcall"><b><u>IF immigration clearance applicable</u></b></h4></div>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "></div>
    
    <?= $form->field($model_immigration, 'arrived_ps')->textInput() ?>

    <?= $form->field($model_immigration, 'pob_inbound')->textInput() ?>

    <?= $form->field($model_immigration, 'first_line_ashore')->textInput() ?>

    <?= $form->field($model_immigration, 'all_fast')->textInput() ?>

    <?= $form->field($model_immigration, 'agent_on_board')->textInput() ?>

    <?= $form->field($model_immigration, 'imi_clearence_commenced')->textInput() ?>

    <?= $form->field($model_immigration, 'imi_clearence_completed')->textInput() ?>

    <?= $form->field($model_immigration, 'pob_outbound')->textInput() ?>

    <?= $form->field($model_immigration, 'cast_off')->textInput() ?>

    <?= $form->field($model_immigration, 'last_line_away')->textInput() ?>

    <?= $form->field($model_immigration, 'cleared_break_water')->textInput() ?>

    <?= $form->field($model_immigration, 'drop_anchor')->textInput() ?>

    <?= $form->field($model_immigration, 'heave_up_anchor')->textInput() ?>

    <?= $form->field($model_immigration, 'pilot_boarded')->textInput() ?>
    
    <hr class="appoint_history" />
    

    <?= $form->field($model, 'eta')->textInput(['tabindex' => 1]) ?>

    <?= $form->field($model, 'dropped_anchor')->textInput(['tabindex' => 6]) ?>

    <?= $form->field($model, 'all_fast')->textInput(['tabindex' => 11]) ?>

    <?= $form->field($model, 'cargo_commenced')->textInput(['tabindex' => 16]) ?>

    <?= $form->field($model, 'cleared_channel')->textInput(['tabindex' => 20]) ?>

    <?= $form->field($model, 'ets')->textInput(['tabindex' => 2]) ?>

    <?= $form->field($model, 'anchor_aweigh')->textInput(['tabindex' => 7]) ?>

    <?= $form->field($model, 'gangway_down')->textInput(['tabindex' => 12]) ?>

    <?= $form->field($model, 'cargo_completed')->textInput(['tabindex' => 17]) ?>

    <?= $form->field($model, 'cosp')->textInput(['tabindex' => 21]) ?>

    <?= $form->field($model, 'eosp')->textInput(['tabindex' => 3]) ?>

    <?= $form->field($model, 'arrived_pilot_station')->textInput(['tabindex' => 8]) ?>

    <?= $form->field($model, 'agent_on_board')->textInput(['tabindex' => 13]) ?>

    <?= $form->field($model, 'pob_outbound')->textInput(['tabindex' => 18]) ?>

    <?= $form->field($model, 'fasop')->textInput(['tabindex' => 22]) ?>

    <?= $form->field($model, 'arrived_anchorage')->textInput(['tabindex' => 4]) ?>

    <?= $form->field($model, 'pob_inbound')->textInput(['tabindex' => 9]) ?>

    <?= $form->field($model, 'immigration_commenced')->textInput(['tabindex' => 14]) ?>

    <div class="form-group "></div>
    <?= $form->field($model, 'eta_next_port')->textInput(['tabindex' => 23]) ?>

    <?= $form->field($model, 'nor_tendered')->textInput(['tabindex' => 5]) ?>

    <?= $form->field($model, 'first_line_ashore')->textInput(['tabindex' => 10]) ?>

    <?= $form->field($model, 'immigartion_completed')->textInput(['tabindex' => 15]) ?>

    <?= $form->field($model, 'lastline_away')->textInput(['tabindex' => 19]) ?>

    <div class="form-group "></div>
    <hr class="appoint_history" />

    <div id="p_scents">
        <span>
            <div class="form-group">
                <label class="control-label">Label</label>
                <input type="text" class="form-control" name="1[label][]">
            </div>
            <div class="form-group ">
                <label class="control-label" for="">Value</label>
                <input type="text" class="form-control" name="1[valuee][]">
            </div>
            <div class="form-group">
                <label class="control-label" >Comment</label>
                <input type="text" class="form-control" name="1[comment][]">
            </div>
        </span>
        <br/>
    </div>



    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
        <a id="addScnt" class="btn btn-icon btn-blue addScnt" ><i class="fa-plus"></i></a>
<!--        <button id="addScnt" class="btn btn-icon btn-blue"  ><i class="fa-plus"></i></button>-->
    </div><br/>
    <hr class="appoint_history" />

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled', 'tabindex' => 24]) ?>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <div class="form-group "></div>
    <br/>
    <?= Html::activeTextarea($model, 'comments', ['class' => 'newsletter-cta-mail txtarea']); ?>
    <?php // $form->field($model, 'comments', ['template' => "<div class='full-width-text'>\n{label}\n{input}\n{hint}\n{error}\n</div>"])->textarea(['rows' => 6, 'tabindex' => 25]) ?>
    <br/>
    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <style>
        .form-inline .form-group {
            width: 16% !important;
            margin-left: 30px;
            margin-bottom: 22px;
        }
        .portcall{
            color:#0f68a6;
        }
        .nav.nav-tabs+.tab-content {
            background-color: #b9c7a7 !important;
            padding: 30px;
            margin-bottom: 30px;
        }
        .form-control {
            width: 100% !important;
            font-weight: bold !important;
        }
        .nav.nav-tabs>li.active>a {
            background-color: #b9c7a7;
        }
        .txtarea{
            width:1220px !important;
            margin-left: 28px;
            height: 150px;
        }
    </style>
</div>
<script>
        $(document).ready(function () {
            /*
             * Add more bnutton function
             */
            var scntDiv = $('#p_scents');
            var i = $('#p_scents span').size() + 1;

            $('#addScnt').on('click', function () {
                var ver = '<span>\n\
                                <div class="form-group">\n\
                                <label class="control-label" for=""></label>\n\
                                <input type="text" id="" class="form-control" name="1[label][]">\n\
                                </div> \n\
                                <div class="form-group">\n\
                                <label class="control-label" for=""></label>\n\
                                <input type="text" class="form-control" name="1[valuee][]">\n\
                                </div> \n\
                                <div class="form-group ">\n\
                                <label class="control-label"></label>\n\
                                <input type="text" id="" class="form-control" name="1[comment][]">\n\
                                </div>\n\
                                <div class="form-group">\n\
                                <a id="remScnt" class="btn btn-icon btn-red remScnt" ><i class="fa-remove"></i></a>\n\
                                 </div><br/>\n\
                                </span>';


                $(ver).appendTo(scntDiv);
                i++;
                return false;
            });
            $('#p_scents').on('click', '.remScnt', function () {
                if (i > 2) {
                    $(this).parents('span').remove();
                    i--;
                }
                return false;
            });
        });
</script>
