<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Services;
use common\models\Currency;
use common\models\Contacts;
use common\models\Debtor;
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
                <div style="float: left;">
                    <?= Html::beginForm(['estimated-proforma/reports'], 'post', ['target' => '_blank']) ?>
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

                                <select name = "principal" id = "" class="form-control">
                                    <option selected = "selected">Select Principal</option>
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
                            <?= Html::submitButton('<i class="fa-print"></i><span>Generate Report</span>', ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
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
                                    <th data-priority="6">COMMENTS</th>
                                    <th data-priority="6">Uploads</th>
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
                                        <td><span class="co-name"><?= $estimate->service->service ?></span></td>
                                        <td><?= $estimate->supplier0->name ?></td>
        <!--                                                                <td><? $estimate->currency0->currency_symbol ?></td>-->
                                        <td><?= $estimate->unit_rate; ?></td>
                                        <td><?= $estimate->unit; ?></td>
        <!--                                                                <td><? $estimate->roe; ?></td>-->
                                        <td><?= $estimate->epda; ?></td>
                                        <td><?= $estimate->principal0->principal_name; ?></td>
                                        <td><?= $estimate->comments; ?></td>
                                        <td><?= $estimate->images; ?></td>
                                        <td>
                                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/appointment/estimated-proforma/add', 'id' => $id, 'prfrma_id' => $estimate->id], ['class' => 'btn btn-icon btn-primary', 'tittle' => 'Edit']) ?>
                                            <?= Html::a('<i class="fa fa-remove"></i>', ['/appointment/estimated-proforma/delete-performa', 'id' => $estimate->id], ['class' => 'btn btn-icon btn-red', 'tittle' => 'Edit']) ?>
                                            <?= Html::a('<i class="fa fa-database"></i>', ['/appointment/sub-services/add', 'id' => $estimate->id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>
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
                                    <td style="font-weight: bold;"><?php echo $epdatotal . '/-'; ?></td>
                                    <td colspan=""></td>
                                    <td colspan=""></td>
                                    <td colspan=""></td>
                                </tr>
                                <tr class="formm">
                                    <?php $form = ActiveForm::begin(); ?>
                                    <td></td>
                                    <td><?= $form->field($model, 'service_id')->dropDownList(ArrayHelper::map(Services::findAll(['status' => 1]), 'id', 'service'), ['prompt' => '-Service-'])->label(false); ?></td>
                                    <td><?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Contacts::findAll(['status' => 1]), 'id', 'name'), ['prompt' => '-Supplier-'])->label(false); ?></td>
                                   <!--<td><?php //$form->field($model, 'currency')->dropDownList(ArrayHelper::map(Currency::findAll(['status' => 1]), 'id', 'currency_name'), ['prompt' => '-Currency-'])->label(false);                                   ?></td>-->
                                    <td><?= $form->field($model, 'unit_rate')->textInput(['placeholder' => 'Unit Rate'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'unit')->textInput(['placeholder' => 'Quantity'])->label(false) ?></td>
                                    <!--<td><?php //$form->field($model, 'roe')->textInput(['placeholder' => 'ROE'])->label(false)                                   ?></td>-->
                                    <td><?= $form->field($model, 'epda')->textInput(['placeholder' => 'EPDA', 'disabled' => true])->label(false) ?></td>

                                    <td><?= $form->field($model, 'principal')->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1, 'id' => explode(',', $appointment->principal)]), 'id', 'principal_name'), ['prompt' => '-Principal-'])->label(false); ?></td>
                                    <td><?= $form->field($model, 'comments')->textInput(['placeholder' => 'Comments'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'images[]')->fileInput(['multiple' => true])->label(false) ?></td>
                                    <td><?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => 'btn btn-success']) ?>
                                    </td>
                                    <?php ActiveForm::end(); ?>
                                </tr>
                                <tr></tr>

                                <!-- Repeat -->

                            </tbody>

                        </table>
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
            <?php //Pjax::end();     ?> 
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
</div>
