<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Contacts;
use common\models\Debtor;
use common\models\OnAccount;
use common\models\Appointment;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\components\AppointmentWidget;
use common\models\FundingAllocation;

/* @var $this yii\web\View */
/* @var $model common\models\EstimatedProforma */

$this->title = 'General Invoice';
$this->params['breadcrumbs'][] = ['label' => ' Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
        .appoint{
                width: 100%;
        }
        .appoint .value{
                font-weight: bold;
                text-align: left;
        }
        .appoint .labell{
                text-align: left;
        }
        .appoint .colen{

        }
        .appoint td{
                padding: 10px;
        }
        .top-content{
                margin-bottom: 25px;
        }
</style>
<div class="row">
        <div class="col-md-12">

                <div class="panel panel-default">
                        <div class="panel-heading">
                                <h2  class="appoint-title panel-title"><?= Html::encode($this->title) . ' # <b style="color: #008cbd;">' . $invoice_data->invoice_number . '</b>' ?></h2>

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
                        <?php //Pjax::begin();          ?>
                        <div class="panel-body">
                                <div class="top-content">
                                        <table class="appoint">
                                                <tr>
                                                        <td class="labell">Invoice Number </td><td class="colen">:</td><td class="value"><?= $invoice_data->invoice_number; ?> </td>
                                                        <td class="labell">Ops ID </td><td class="colen">:</td><td class="value"><?= $invoice_data->oops_id; ?> </td>
                                                        <td class="labell">On Account Of </td><td class="colen">:</td><td class="value">
                                                                <?php
                                                                if ($invoice_data->on_account_of == 1) {
                                                                        $account_of = 'CUSTOMS GATE PASS';
                                                                } elseif ($invoice_data->on_account_of == 2) {
                                                                        $account_of = 'CARGO CLEARANCE';
                                                                } elseif ($invoice_data->on_account_of == 3) {
                                                                        $account_of = 'EQUIPMENT HIRE';
                                                                } elseif ($invoice_data->on_account_of == 4) {
                                                                        $account_of = 'TRUCK CLEARANCE';
                                                                }
                                                                ?>
                                                                <?= $account_of; ?>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td class="labell">Job </td><td class="colen">:</td><td class="value">
                                                                <?php
                                                                if ($invoice_data->job == 1) {
                                                                        $job = 'SERVICE / ATTENDANCE';
                                                                } elseif ($invoice_data->job == 2) {
                                                                        $job = 'AGENENTS / ATTENDANCE';
                                                                }
                                                                ?>
                                                                <?= $job; ?>

                                                        </td>
                                                        <td class="labell">Payment Terms </td><td class="colen">:</td><td class="value">
                                                                <?php
                                                                if ($invoice_data->payment_terms == 1) {
                                                                        $cash = 'CASH';
                                                                } elseif ($invoice_data->job == 2) {
                                                                        $cash = 'CHEQUE';
                                                                }
                                                                ?>
                                                                <?= $cash; ?>

                                                        </td>
                                                        <td class="labell">Doc No </td><td class="colen">:</td><td class="value"><?= $invoice_data->doc_no; ?> </td>
                                                </tr>

                                        </table>
                                </div>
                                <?= Html::a('<i class="fa-th-list"></i><span> Manage Invoice</span>', ['generate-invoice/index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                <?= Html::a('<i class="fa-th-list"></i><span> Create Invoice</span>', ['generate-invoice/create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                <?= Html::a('<i class="fa-th-list"></i><span> Generate Invoice</span>', ['generate-invoice/reports', 'id' => $id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone', 'target' => '_blank']) ?>
                                <ul class="estimat nav nav-tabs nav-tabs-justified">
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Generate Invoice</span>', ['generate-invoice/update', 'id' => $id]);
                                                ?>

                                        </li>
                                        <li class="active">
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Invoice Description</span>', ['generate-invoice/add', 'id' => $id]);
                                                ?>

                                        </li>
                                </ul>
                                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                        <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                        <tr>
                                                                <th>#</th>
                                                                <th>Description</th>
                                                                <th>Quantity</th>
                                                                <th>Unit Price</th>
                                                                <th>Total</th>
                                                                <th data-priority="1">ACTIONS</th>
                                                        </tr>
                                                </thead>


                                                <tbody>
                                                        <?php
                                                        $i = 0;
                                                        $totalamount = 0;
                                                        $flag = 0;
                                                        foreach ($invoice_details as $invoice_detail) {
                                                                $i++;
                                                                ?>
                                                                <tr>
                                                                        <td><?= $i; ?></td>
                                                                        <td><?= $invoice_detail->description; ?></td>
                                                                        <td><?= $invoice_detail->qty; ?></td>
                                                                        <td><?= $invoice_detail->unit_price; ?></td>
                                                                        <td><?= $invoice_detail->total; ?></td>
                                                                        <td>
                                                                                <?= Html::a('<i class="fa fa-pencil"></i>', ['/invoice/generate-invoice/add', 'id' => $id, 'invoice_details_id' => $invoice_detail->id], ['class' => '']) ?>
                                                                                <?= Html::a('<i class="fa-remove"></i>', ['/invoice/generate-invoice/delete-invoice', 'id' => $invoice_detail->id], ['class' => '', 'data-confirm' => 'Are you sure you want to delete this item?']) ?>
                                                                        </td>

                                                                        <?php
                                                                        $totalamount += $invoice_detail->total;
                                                                        ?>
                                                                </tr>
                                                                <?php
                                                        }
                                                        ?>

                                                        <tr>
                                                                <td colspan="4">Total</td>
                                                                <td><?= $totalamount ?></td>
                                                                <td></td>
                                                        </tr>
                                                </tbody>

                                        </table>
                                </div>

                                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                        <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                        <tr>
                                                                <th>Description</th>
                                                                <th>Quantity</th>
                                                                <th>Unit Price</th>
                                                                <th>Total</th>
                                                                <th data-priority="1">ACTIONS</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <tr class="filter">
                                                                <?php $form = ActiveForm::begin(); ?>

                                                                <td><?= $form->field($model, 'description')->textInput(['placeholder' => 'Description'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'qty')->textInput(['placeholder' => 'Quantity'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'unit_price')->textInput(['placeholder' => 'Unit Price'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'total')->textInput(['placeholder' => 'Total'])->label(false) ?></td>
                                                                <td><?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => 'btn btn-success']) ?>
                                                                </td>
                                                                <?php ActiveForm::end(); ?>


                                                </tbody>
                                        </table>
                                        <div>
                                                <?php
                                                // echo Html::a('<span>Back to Close Estimate</span>', ['/appointment/close-estimate/add', 'id' => $appointment->id], ['class' => 'btn btn-secondary']);
                                                ?>
                                        </div>
                                </div>





                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>

                                <script>
                                        $(document).ready(function () {
                                                $("#invoicegeneratedetails-unit_price").keyup(function () {
                                                        multiply();
                                                });
                                                $("#invoicegeneratedetails-qty").keyup(function () {
                                                        multiply();
                                                });
                                        });
                                        function multiply() {
                                                var rate = $("#invoicegeneratedetails-unit_price").val();
                                                var unit = $("#invoicegeneratedetails-qty").val();
                                                if (rate != '' && unit != '') {
                                                        $("#invoicegeneratedetails-total").val(rate * unit);
                                                }

                                        }
                                        $("#invoicegeneratedetails-total").prop("disabled", true);
                                </script>
                        </div>
                        <?php //Pjax::end();              ?>
                </div>
        </div>
</div>
<!--<a href="javascript:;" onclick="showAjaxModal();" class="btn btn-primary btn-single btn-sm">Show Me</a>
 Modal code
<script type="text/javascript">
        function showAjaxModal(id)
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
             */
        }
</script>-->
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
        </style>
</div>
