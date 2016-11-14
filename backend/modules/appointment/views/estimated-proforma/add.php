<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Services;
use common\models\Currency;
use common\models\Contacts;
use common\models\Debtor;
use common\models\EstimateReport;
use common\models\UploadFile;
use common\models\Appointment;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\components\AppointmentWidget;

/* @var $this yii\web\View */
/* @var $model common\models\EstimatedProforma */

$this->title = 'Create Estimated Proforma';
$this->params['breadcrumbs'][] = ['label' => 'Estimated Proformas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2  class="appoint-title panel-title"><?= Html::encode($this->title) . ' # <b style="color: #008cbd;">' . $appointment->appointment_no . '</b>' ?></h2>

                <div class="panel-options">
                    <a href="#" data-toggle="panel">
                        <span class="collapse-icon">&ndash;</span>
                        <span class="expand-icon">+</span>
                    </a>
                    <a href="#" data-toggle="remove">
                        &times;
                    </a>
                </div>
            </div>
            <?php //Pjax::begin();  ?> 
            <div class="panel-body">
                <?= AppointmentWidget::widget(['id' => $appointment->id]) ?>

                <hr class="appoint_history" />
                <div class="error">
                    <?php //if (Yii::$app->session->hasFlash('error')): ?>
                    <?= Yii::$app->session->getFlash('error'); ?>
                    <?php // endif; ?>
                </div>
                <script>
                        $(document).ready(function () {
                            $('#epda-form').submit(function (e) {
                                var val = $('#epda_princi').val();
                                if (val != '') {
                                    window.open('about:blank', 'print_popup', 'width=1000,height=500');
                                } else {
                                    alert('please choose a Principal');
                                    e.preventDefault();
                                    return false;
                                }

                            });
                        });
                </script>
                <div style="float: left;">

                    <?= Html::beginForm(['estimated-proforma/reports'], 'post', ['target' => 'print_popup', 'id' => "epda-form"]) ?>
<!--<form name="estimate" action="<?= Yii::$app->homeUrl ?>appointment/estimated-proforma/reports" method="post">-->
                    <?php
                    $arr = explode(',', $appointment->principal);
                    if (count($arr) == 1) {
                            ?>
                            <div class="row">
                                <div class="col-md-8" style="float:left;"> 
                                    <input type="hidden" name="app_id" value="<?= $appointment->id ?>">
                                    <input type="hidden" name="principal" value="<?= $arr[0]; ?>">  
                                    <?php
                            } else {
                                    ?>

                                    <input type="hidden" name="app_id" value="<?= $appointment->id ?>">

                                    <select name = "principal" id = "epda_princi" class="form-control">
                                        <option value="">Select Principal</option>
                                        <?php
                                        foreach ($arr as $key => $value) {
                                                $data = Debtor::findOne(['id' => $value]);
                                                ?>
                                                <option value="<?= $value ?>"><?= $data->principal_name ?></option>
                                        <?php }
                                        ?>
                                    </select> 
                                </div>

                                <?php
                        }
                        ?>

                        <div class="col-md-4" style="float:left;">
                            <?= Html::submitButton('<i class="fa-print"></i><span>Generate EPDA</span>', ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
   <!--<input type="submit" name="b1" value="Submit">-->
                            <?= Html::endForm() ?>
                            <?php
//                    echo Html::a('<i class="fa-print"></i><span>Generate Report</span>', ['estimated-proforma/report', 'id' => $appointment->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']);
                            ?> 
                        </div>   
                    </div>
                </div>

                <ul class="estimat nav nav-tabs nav-tabs-justified">
                    <li>
                        <?php
                        echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Appointment</span>', ['appointment/update', 'id' => $appointment->id]);
                        ?>

                    </li>
                    <li class="active">
                        <?php
                        echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Estimated Proforma</span>', ['estimated-proforma/add', 'id' => $appointment->id]);
                        ?>

                    </li>
                    <li>
                        <?php
                        echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Port call Data</span>', ['port-call-data/update', 'id' => $appointment->id]);
                        ?>

                    </li>
                    <li>
                        <?php
                        echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Close Estimate</span>', ['close-estimate/add', 'id' => $appointment->id]);
                        ?>

                    </li>
                </ul>
                <div class="outterr">

                    <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                        <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">SERVICES</th>
                                    <th data-priority="3">SUPPLIER</th>
    <!--                                                                <th data-priority="3">CURRENCY</th>-->
                                    <th data-priority="1">RATE /QTY</th>
                                    <th data-priority="3">QTY</th>
    <!--                                                                <th data-priority="6">ROE</th>-->
                                    <th data-priority="6" >EPDA VALUE</th>
                                    <th data-priority="6">PRINCIPAL</th>
                                    <th data-priority="6">RATE TO CATEGORY</th>
                                    <th data-priority="6">COMMENTS</th>
                                    <!--<th data-priority="6">Uploads</th>-->
                                    <th data-priority="1">ACTIONS</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($estimates as $estimate):
                                        $i++;
                                        ?>
                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td><span class="" drop_id="estimatedproforma-service_id" id="<?= $estimate->id ?>-service_id" val="<?= $estimate->service_id ?>"><?= $estimate->service->service ?></span></td>
                                            <td><span class="" drop_id="estimatedproforma-supplier" id="<?= $estimate->id ?>-supplier" val="<?= $estimate->supplier ?>"><?= $estimate->supplier0->name ?></span></td>
                                            <td><span class="edit_text" id="<?= $estimate->id ?>-unit_rate"  val="<?= $estimate->unit_rate ?>">
                                                    <?php
                                                    if ($estimate->unit_rate == '') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                            echo Yii::$app->SetValues->NumberFormat($estimate->unit_rate);
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td><span class="edit_text" id="<?= $estimate->id ?>-unit" val="<?= $estimate->unit ?>">
                                                    <?php
                                                    if ($estimate->unit == '') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                            echo Yii::$app->SetValues->NumberFormat($estimate->unit);
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td><span class="edit_text" id="<?= $estimate->id ?>-epda" val="<?= $estimate->epda ?>">
                                                    <?php
                                                    if ($estimate->epda == '') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                            echo Yii::$app->SetValues->NumberFormat($estimate->epda);
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td><span class="edit_dropdown" drop_id="estimatedproforma-principal" id="<?= $estimate->id ?>-principal" val="<?= $estimate->principal ?>">
                                                    <?php
                                                    if ($estimate->principal == '') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                            echo $estimate->principal0->principal_id;
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td><span class="edit_text" id="<?= $estimate->id ?>-rate_to_category" val="<?= $estimate->rate_to_category ?>">
                                                    <?php
                                                    if ($estimate->rate_to_category == '') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                            if (strlen($estimate->rate_to_category) > 15) {
                                                                    echo substr($estimate->rate_to_category, 0, 15) . '...';
                                                            } else {
                                                                    echo $estimate->rate_to_category;
                                                            }
                                                    }
                                                    ?>

                                                </span>
                                            </td>
                                            <td><span class="edit_text" id="<?= $estimate->id ?>-comments" val="<?= $estimate->comments ?>">
                                                    <?php
                                                    if ($estimate->comments == '') {
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                            if (strlen($estimate->comments) > 15) {
                                                                    echo substr($estimate->comments, 0, 15) . '...';
                                                            } else {
                                                                    echo $estimate->comments;
                                                            }
                                                    }
                                                    ?>

                                                </span>
                                            </td>
                                            <!--<td><?php // $estimate->images;                                         ?></td>-->
                                            <td>
                                                <?= Html::a('<i class="fa fa-pencil"></i>', ['/appointment/estimated-proforma/add', 'id' => $id, 'prfrma_id' => $estimate->id], ['class' => '', 'tittle' => 'Edit']) ?>
                                                <?= Html::a('<i class="fa fa-remove"></i>', ['/appointment/estimated-proforma/delete-performa', 'id' => $estimate->id], ['class' => '', 'tittle' => 'Edit', 'data-confirm' => 'Are you sure you want to delete this item?']) ?>
                                                <?= Html::a('<i class="fa fa-database"></i>', ['/appointment/sub-services/add', 'id' => $estimate->id], ['class' => '', 'target' => '_blank']) ?>
        <!--                                            <a href="javascript:;" onclick="showAjaxModal(<?= $estimate->id ?>);" class="btn btn-success">Sub</a>-->
                                                <?php //Html::a('Sub', [''], ['class' => 'btn btn-success', "onclick" => "showAjaxModal(".$estimate->id.");"])   ?>
                                            </td>
                                            <?php
                                            $epdatotal += $estimate->epda;
                                            ?>
                                        </tr>	

                                        <?php
                                endforeach;
                                ?>
                                <tr>
                                    <td></td>
                                    <td colspan="4"> <b>EPDA TOTAL</b></td>
                                    <td style="font-weight: bold;"><?php echo Yii::$app->SetValues->NumberFormat($epdatotal) . '/-'; ?></td>
                                    <td colspan=""></td>
                                    <td colspan=""></td>
                                    <td colspan=""></td>
                                    <td colspan=""></td>
                                </tr>
                                <tr class="formm">
                                    <?php $form = ActiveForm::begin(); ?>
                                    <td></td>
                                    <td><?= $form->field($model, 'service_id')->dropDownList(ArrayHelper::map(Services::findAll(['status' => 1]), 'id', 'service'), ['prompt' => '-Service-'])->label(false); ?></td>
                                    <td><?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Contacts::findAll(['status' => 1]), 'id', 'name'), ['prompt' => '-Supplier-'])->label(false); ?></td>
                                   <!--<td><?php //$form->field($model, 'currency')->dropDownList(ArrayHelper::map(Currency::findAll(['status' => 1]), 'id', 'currency_name'), ['prompt' => '-Currency-'])->label(false);                                                                           ?></td>-->
                                    <td><?= $form->field($model, 'unit_rate')->textInput(['placeholder' => 'Unit Rate'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'unit')->textInput(['placeholder' => 'Quantity'])->label(false) ?></td>
                                    <!--<td><?php //$form->field($model, 'roe')->textInput(['placeholder' => 'ROE'])->label(false)                                                                           ?></td>-->
                                    <td><?= $form->field($model, 'epda')->textInput(['placeholder' => 'EPDA', 'disabled' => true])->label(false) ?></td>
                                    <?php
                                    $arr1 = explode(',', $appointment->principal);
                                    if (count($arr1) == 1) {
                                            foreach ($arr1 as $value) {
                                                    ?>
                                                    <td><div class="form-group field-estimatedproforma-principal">

                                                            <select id="estimatedproforma-principal" class="form-control" name="EstimatedProforma[principal]">
                                                                <option value="<?= $value ?>"><?= $appointment->getClintCode($value); ?></option>
                                                            </select>

                                                            <div class="help-block"></div>
                                                        </div></td>
                                                    <?php
                                            }
                                    } else {
                                            ?>
                                            <td><?= $form->field($model, 'principal')->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1, 'id' => explode(',', $appointment->principal)]), 'id', 'principal_id'), ['prompt' => '-Principal-'])->label(false); ?></td>
                                            <?php
                                    }
                                    ?>
                                    <td><?= $form->field($model, 'rate_to_category')->textInput(['placeholder' => 'Rate to Category'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'comments')->textInput(['placeholder' => 'Comments'])->label(false) ?></td>
                                    <!--<td><?php // $form->field($model, 'images[]')->fileInput(['multiple' => true])->label(false)                                         ?></td>-->
                                    <td><?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => 'btn btn-success']) ?>
                                    </td>
                                    <?php ActiveForm::end(); ?>
                                </tr>
                                <tr></tr>

                                <!-- Repeat -->

                            </tbody>

                        </table>
                        <br/>
                        <div style="margin-left: 46%;">
                            <?php // Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['estimatePath']);  ?>
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => Yii::$app->homeUrl . 'appointment/estimated-proforma/uploads', 'method' => 'post']) ?>
                            <?php
                            $model_upload->appointment_id = $appointment->id;
                            $model_upload->type = Yii::$app->params['estimatePath'];
                            ?>
                            <?php //$form->field($model_upload, 'filee[]')->fileInput(['multiple' => true]) ?>
                            <?= $form->field($model_upload, 'filee[]')->fileInput(['multiple' => true]) ?>
                            <?= $form->field($model_upload, 'appointment_id')->hiddenInput()->label(false) ?>
                            <?= $form->field($model_upload, 'type')->hiddenInput()->label(false) ?>
                            <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>


                            <?php ActiveForm::end() ?>  
                        </div>
                        <br/>
                        <hr class="appoint_history" />
                        <div class="display-uploads" style="margin-bottom: 25px;">
                            <div class="row" style="display:inline-block">
                                <div class="col-md-4" style="float:left;text-align: center;">
                                    <table>
                                        <tr>
                                            <td style="border: 1px solid black;"><h4 class="sub-heading">Previously Generated EPDA'S</h4></td>
                                        </tr>
                                        <?php
                                        $estmate_reports = EstimateReport::findAll(['appointment_id' => $appointment->id]);
                                        ?>
                                        <?php foreach ($estmate_reports as $estmate_report) { ?>
                                                <tr>
                                                    <td style="border: 1px solid black;padding: 10px;">
                                                        <?php echo Html::a($estmate_report->date_time, ['/appointment/estimated-proforma/show-report'], ['onclick' => "window.open('show-report?id=$estmate_report->id', 'newwindow', 'width=750, height=500');return false;"]) . '&nbsp;&nbsp;<a href="remove-report?id=' . $estmate_report->id . '"><i class="fa fa-remove"></i></a>'; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                        }
                                        ?>

                                    </table>
                                </div>
                                <div class="col-md-8" style="float:left;text-align: center;">
                                    <table>
                                        <tr>
                                            <td style="border: 1px solid black;"><h4 class="sub-heading">Uploaded Files</h4></td>
                                        </tr>
                                        <?php
                                        if (!empty(Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['estimatePath']))) {
                                                ?>
                                                <tr>
                                                    <td style="border: 1px solid black;padding: 10px;">
                                                        <?= Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['estimatePath']); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                        }
                                        ?>

                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <script>
                        $("document").ready(function () {
                            $('#estimatedproforma-service_id').change(function () {
                                var service_id = $(this).val();
                                $.ajax({
                                    type: 'POST',
                                    cache: false,
                                    data: {service_id: service_id},
                                    url: '<?= Yii::$app->homeUrl; ?>/appointment/estimated-proforma/supplier',
                                    success: function (data) {
                                        if (data != '') {
                                            $("#estimatedproforma-supplier").html(data);
                                        } else {
                                            $("#estimatedproforma-supplier").prop('disabled', true);
                                        }
                                    }
                                });
                            });

                        });
                </script>
                <script type="text/javascript">
                        jQuery(document).ready(function ($)
                        {
                            $("#estimatedproforma-service_id").select2({
                                //placeholder: 'Select your country...',
                                allowClear: true
                            }).on('select2-open', function ()
                            {
                                // Adding Custom Scrollbar
                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            });
                            //                            $("#estimatedproforma-supplier").select2({
                            //                                //placeholder: 'Select your country...',
                            //                                allowClear: true
                            //                            }).on('select2-open', function ()
                            //                            {
                            //                                // Adding Custom Scrollbar
                            //                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            //                            });
                            $("#estimatedproforma-currency").select2({
                                //placeholder: 'Select your country...',
                                allowClear: true
                            }).on('select2-open', function ()
                            {
                                // Adding Custom Scrollbar
                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            });
                            $("#estimatedproforma-principal").select2({
                                //placeholder: 'Select your country...',
                                allowClear: true
                            }).on('select2-open', function ()
                            {
                                // Adding Custom Scrollbar
                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            });
                        });</script>


                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>

                <script>
                        $(document).ready(function () {
                            $("#estimatedproforma-unit_rate").keyup(function () {
                                multiply();
                            });
                            $("#estimatedproforma-unit").keyup(function () {
                                multiply();
                            });
                        });
                        function multiply() {
                            var rate = $("#estimatedproforma-unit_rate").val();
                            var unit = $("#estimatedproforma-unit").val();
                            if (rate != '' && unit != '') {
                                $("#estimatedproforma-epda").val(rate * unit);
                            }

                        }
                        $("#estimatedproforma-epda").prop("disabled", true);
                </script>
                <div style="float:right;padding-top: 5px;">
                    <?php
                    echo Html::a('<span> Estimated Proforma Completed & Proceed to Portcall</span>', ['estimated-proforma/estimate-confirm', 'id' => $appointment->id], ['class' => 'btn btn-secondary']);
                    ?>      

                </div>

            </div>
            <?php //Pjax::end();      ?> 
        </div>

    </div>
</div>
<!--<a href="javascript:;" onclick="showAjaxModal();" class="btn btn-primary btn-single btn-sm">Show Me</a>-->
<!-- Modal code -->
<script type="text/javascript">
        /*function showAjaxModal(id)
         {
         jQuery('#add-sub').modal('show', {backdrop: 'static'});
         jQuery('#add-sub .modal-body').html(id);
         /*setTimeout(function ()
         {
         jQuery.ajax({
         url: "data/ajax-content.txt",
         success: function (response)
         {
         jQuery('#modal-7 .modal-body').html(response);
         }
         });
         }, 800); // just an example
         
         }*/
</script>
<div class="modal fade" id="add-sub">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Dynamic Content</h4>
            </div>

            <div class="modal-body">

                Content is loading...

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Save changes</button>
            </div>
        </div>
    </div>
    <style>
        .filter{
            background-color: #b9c7a7;
        }
        table.table tr td:last-child a {
            padding: inherit;padding: 0px 4px;
        }
        .error{
            color: #0553b1;
            padding-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
        }

    </style>
    <script>
            $("document").ready(function () {

                /*  
                 * Double click enter function
                 * */

                $('.edit_text').on('dblclick', function () {

                    var val = $(this).attr('val');
                    var idd = this.id;
                    var res_data = idd.split("-");
                    if (res_data[1] == 'comments' || res_data[1] == 'rate_to_category') {
                        $(this).html('<textarea class="' + idd + '" value="' + val + '">' + val + '</textarea>');

                    } else {
                        $(this).html('<input class="' + idd + '" type="text" value="' + val + '"/>');

                    }

                    $('.' + idd).focus();
                });
                $('.edit_text').on('focusout', 'input,textarea', function () {
                    var thiss = $(this).parent('.edit_text');
                    var data_id = thiss.attr('id');
                    var update = thiss.attr('update');
                    var res_id = data_id.split("-");
                    var res_val = $(this).val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        data: {id: res_id[0], name: res_id[1], valuee: res_val},
                        url: '<?= Yii::$app->homeUrl; ?>/appointment/estimated-proforma/edit-estimate',
                        success: function (data) {
                            thiss.html(res_val);
                        }
                    });

                });

                /*  
                 * Double click Dropdown
                 * */

                $('.edit_dropdown').on('dblclick', function () {
                    var val = $(this).attr('val');
                    var drop_id = $(this).attr('drop_id');
                    var idd = this.id;
                    var option = $('#' + drop_id).html();
                    $(this).html('<select class="' + drop_id + '" value="' + val + '">' + option + '</select>');
                    $('.' + drop_id + ' option[value="' + val + '"]').attr("selected", "selected");
                    $('.' + drop_id).focus();

                });
                $('.edit_dropdown').on('focusout', 'select', function () {
                    var thiss = $(this).parent('.edit_dropdown');
                    var data_id = thiss.attr('id');
                    var res_id = data_id.split("-");
                    var res_val = $(this).val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        data: {id: res_id[0], name: res_id[1], valuee: res_val},
                        url: '<?= Yii::$app->homeUrl; ?>/appointment/estimated-proforma/edit-estimate-service',
                        success: function (data) {
                            thiss.html(data);
                        }
                    });

                });


            });
    </script>
</div>
