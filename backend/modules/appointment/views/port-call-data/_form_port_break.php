<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PortBreakTimings;
use common\models\PortCargoDetails;

/* @var $this yii\web\View */
/* @var $model common\models\PortBreakTimings */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="port-call-data-form form-inline">
    <div class="port-cargo-details-form form-inline">
        <h4 class="headstyle">Cargo Details</h4>
        <hr class="appoint_history" />
        <?php $form = ActiveForm::begin(['action' => Yii::$app->homeUrl . '/appointment/port-call-data/port-break', 'method' => 'post',]); ?>

        <?= $form->field($model_port_cargo_details, 'cargo_type')->textarea(['rows' => 6]) ?>

        <?= $form->field($model_port_cargo_details, 'cargo_document')->textarea(['rows' => 6]) ?>

        <?= $form->field($model_port_cargo_details, 'remarks')->textarea(['rows' => 6]) ?>

        <?= $form->field($model_port_cargo_details, 'masters_comment')->textarea(['rows' => 6]) ?>

        <?= $form->field($model_port_cargo_details, 'loaded_quantity')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model_port_cargo_details, 'bl_quantity')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model_port_cargo_details, 'stoppages_delays')->textInput(['maxlength' => true]) ?>
    </div>
    <h4 class="headstyle">Port Break Timings</h4>
    <hr class="appoint_history" />
    <?php // Html::beginForm(['port-call-data/port-break'], 'post', ['target' => '_blank'])  ?>
    <div id = "port_break">
        <input type="hidden" id="app_id"  name="app_id" value="<?= $model_appointment->id; ?>">
        <input type="hidden" id="delete_port_break"  name="delete_port_break" value="">
        <?php
        if (!empty($model_port_break)) {

                foreach ($model_port_break as $data) {
                        ?>
                        <span>
                            <div class="form-group">
                                <input type="text" class="form-control" name="updatee[<?= $data->id; ?>][label][]" value="<?= $data->label; ?>" required>
                            </div>
                            <div class="form-group ">
                                <input type="text" class="form-control" name="updatee[<?= $data->id; ?>][value][]" value="<?= $data->value; ?>" required>
                            </div>
                            <div class="form-group">
                                <a id="remScnt" val="<?= $data->id; ?>" class="btn btn-icon btn-red remScnt" ><i class="fa-remove"></i></a>
                            </div>
                        </span>
                        <br>
                        <?php
                }
        }
        ?>
        <br>
        <span>
            <div class="form-group">
                <label class="control-label">Label</label>
                <input type="text" class="form-control" name="create[label][]">
            </div>
            <div class="form-group ">
                <label class="control-label" for="">Value</label>
                <input type="text" class="form-control" name="create[valuee][]">
            </div>
        </span>
        <br/>
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
        <a id="addportbreak" class="btn btn-icon btn-blue addScnt" ><i class="fa-plus"></i></a>
    <!--        <button id="addScnt" class="btn btn-icon btn-blue"  ><i class="fa-plus"></i></button>-->
    </div><br/>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
   
    
    <?php // Html::submitButton('<span>SAVE</span>', ['class' => 'btn btn-primary'])  ?>
   
   
    
    <h4 class="headstyle">Add Stoppages / Delays - Description</h4>
    <hr class="appoint_history" />
    <?php // Html::beginForm(['port-call-data/port-stoppages'], 'post') ?>
    <div id = "port_stoppages">
        <input type="hidden" id="app_id"  name="app_id" value="<?= $model_appointment->id; ?>">
        <input type="hidden" id="delete_port_stoppages"  name="delete_port_stoppages" value="">
        <?php
        if (!empty($model_port_stoppages)) {

                foreach ($model_port_stoppages as $data) {
                        ?>
                        <span>
                            <div class="form-group">
                                <input type="text" class="form-control" name="updatee1[<?= $data->id; ?>][stoppage_from][]" value="<?= $data->stoppage_from; ?>" required>
                            </div>
                            <div class="form-group ">
                                <input type="text" class="form-control" name="updatee1[<?= $data->id; ?>][stoppage_to][]" value="<?= $data->stoppage_to; ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="updatee1[<?= $data->id; ?>][comment][]" value="<?= $data->comment; ?>" required>
                            </div>
                            <div class="form-group">
                                <a id="remScnt" val="<?= $data->id; ?>" class="btn btn-icon btn-red remScnt" ><i class="fa-remove"></i></a>
                            </div>
                        </span>
                        <br>
                        <?php
                }
        }
        ?>
        <br>
        <span>
            <div class="form-group">
                <label class="control-label">From</label>
                <input type="text" class="form-control" name="create1[stoppage_from][]">
            </div>
            <div class="form-group ">
                <label class="control-label" for="">To</label>
                <input type="text" class="form-control" name="create1[stoppage_to][]">
            </div>
            <div class="form-group">
                <label class="control-label" >Comment</label>
                <input type="text" class="form-control" name="create1[comment][]">
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
        <a id="addportstoppages" class="btn btn-icon btn-blue addScnt" ><i class="fa-plus"></i></a>
    <!--        <button id="addScnt" class="btn btn-icon btn-blue"  ><i class="fa-plus"></i></button>-->
    </div><br/>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <div class="form-group field-portcalldatarob-fresh_water_arrival_quantity">
    </div>
    <?php // Html::submitButton('<span>SAVE</span>', ['class' => 'btn btn-primary']) ?>
     <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>
     <?php ActiveForm::end(); ?>
</div>
<style>
    .headstyle{
        color:#03a9f4;
    }
    .appoint_history1{
        margin-top: 18px;
        margin-bottom: 18px;
        border: 0;
        border-top:2px solid black;
    }
</style>
<script>
        $(document).ready(function () {
            /*
             * Add more bnutton function
             */
            var scntDiv = $('#port_break');
            var i = $('#port_break span').size() + 1;

            $('#addportbreak').on('click', function () {
                var ver = '<span>\n\
                                <div class="form-group">\n\
                                <label class="control-label" for=""></label>\n\
                                <input type="text" class="form-control" name="create[label][]">\n\
                                </div> \n\
                                <div class="form-group">\n\
                                <label class="control-label" for=""></label>\n\
                                <input type="text" class="form-control" name="create[valuee][]">\n\
                                </div>\n\
                                <div class="form-group">\n\
                                <a id="remScnt" class="btn btn-icon btn-red remScnt" ><i class="fa-remove"></i></a>\n\
                                 </div><br/>\n\
                                </span>';


                $(ver).appendTo(scntDiv);
                i++;
                return false;
            });
            $('#port_break').on('click', '.remScnt', function () {
                if (i > 2) {
                    $(this).parents('span').remove();
                    i--;
                }
                if (this.hasAttribute("val")) {
                    var valu = $(this).attr('val');
                    $('#delete_port_break').val($('#delete_port_break').val() + valu + ',')
                }
                return false;
            });

        });
</script>
<script>
        $(document).ready(function () {
            /*
             * Add more bnutton function
             */
            var scntDiv = $('#port_stoppages');
            var i = $('#port_stoppages span').size() + 1;

            $('#addportstoppages').on('click', function () {
                var ver = '<span>\n\
                                <div class="form-group">\n\
                                <label class="control-label" for=""></label>\n\
                                <input type="text" class="form-control" name="create1[stoppage_from][]" required>\n\
                                </div> \n\
                                <div class="form-group">\n\
                                <label class="control-label" for=""></label>\n\
                                <input type="text" class="form-control" name="create1[stoppage_to][]">\n\
                                </div> \n\
                                <div class="form-group ">\n\
                                <label class="control-label"></label>\n\
                                <input type="text" id="" class="form-control" name="create1[comment][]" required>\n\
                                </div>\n\
                                <div class="form-group">\n\
                                <a id="remScnt" class="btn btn-icon btn-red remScnt" ><i class="fa-remove"></i></a>\n\
                                 </div><br/>\n\
                                </span>';


                $(ver).appendTo(scntDiv);
                i++;
                return false;
            });
            $('#port_stoppages').on('click', '.remScnt', function () {
                if (i > 2) {
                    $(this).parents('span').remove();
                    i--;
                }
                if (this.hasAttribute("val")) {
                    var valu = $(this).attr('val');
                    $('#delete_port_stoppages').val($('#delete_port_stoppages').val() + valu + ',')
                }
                return false;
            });

            $('.portcall').click(function () {
                $('.hidediv').slideToggle();
            });
        });
</script>

