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

$this->title = 'Funding allocation';
$this->params['breadcrumbs'][] = ['label' => ' Pre-Funding', 'url' => ['index']];
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
                        <?php //Pjax::begin();      ?>
                        <div class="panel-body">
                                <?= AppointmentWidget::widget(['id' => $appointment->id]) ?>
                                <div class="col-lg-12">
                                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                                                <div class="alert alert-danger" role="alert">
                                                        <?= Yii::$app->session->getFlash('error') ?>
                                                </div>
                                        <?php endif; ?>
                                </div>
                                <hr class="appoint_history" />
                                <ul class="estimat nav nav-tabs nav-tabs-justified">
                                        <li class="active">
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Pre-Funding</span>', ['funding-allocation/add', 'id' => $appointment->id]);
                                                ?>

                                        </li>
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Actual Price</span>', ['actual-funding/add', 'id' => $appointment->id]);
                                                ?>

                                        </li>
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Supplier Funding</span>', ['supplier-funding/add', 'id' => $appointment->id]);
                                                ?>

                                        </li>
                                </ul>
                                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                        <?php
                                        $principp = explode(',', $appointment->principal);
                                        foreach ($principp as $value) {
                                                $onaccount = OnAccount::find()->orderBy(['id' => SORT_DESC])->where(['debtor_id' => $value])->one();
                                                ?>
                                                <h5 style="font-weight:bold;color: #f44336;">On Account Balance for :<?= $appointment->getDebtorName($value) ?> - <?= Yii::$app->SetValues->NumberFormat($onaccount->balance) ?> /-</h5>
                                                <?php
                                        }
                                        foreach ($principp as $value) {
                                                $funds = FundingAllocation::findAll(['appointment_id' => $appointment->id, 'principal_id' => $value]);
                                                if (!empty($funds)) {
                                                        ?>
                                                        <h5 style="font-weight:bold;color: #008cbd;">Principal Name :<?= $appointment->getDebtorName($value); ?></h5>
                                                        <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                                                <thead>
                                                                        <tr>
                                                                                <th>#</th>
                                                                                <th>Payment Mode</th>
                                                                                <th>Description</th>
                                                                                <th>Date</th>
                                                                                <th>Payment Type</th>
                                                                                <th>Check Number</th>
                                                                                <th>Amount</th>
                                                                                <th>Outstanding</th>
                                                                                <th data-priority="1">ACTIONS</th>
                                                                        </tr>
                                                                </thead>
                                                                <?php
                                                                $j = 0;
                                                                $totalamount = 0;
                                                                $flag = 0;
                                                                foreach ($funds as $fund) {
                                                                        $j++;
                                                                        ?>
                                                                        <tbody>
                                                                                <tr class="filter">
                                                                                        <td><?= $j; ?></td>
                                                                                        <?php
                                                                                        if ($fund->type == 1) {
                                                                                                $fund_type = 'Credit';
                                                                                        } elseif ($fund->type == 2) {
                                                                                                $fund_type = 'Debit';
                                                                                        } elseif ($fund->type == 3) {
                                                                                                $fund_type = 'EPDA';
                                                                                        } else {
                                                                                                $fund_type = 'FDA';
                                                                                        }
                                                                                        ?>
                                                                                        <td><?= $fund_type; ?></td>
                                                                                        <td><?= $fund->description; ?></td>
                                                                                        <td><?= Yii::$app->SetValues->DateFormate($fund->fund_date); ?></td>
                                                                                        <?php
                                                                                        if ($fund->payment_type == 1) {
                                                                                                $payment_type = 'Cash';
                                                                                        } else {
                                                                                                $payment_type = 'Check';
                                                                                        }
                                                                                        ?>
                                                                                        <td><?= $payment_type; ?></td>
                                                                                        <td><?= $fund->check_no; ?></td>
                                                                                        <td><?= $fund->amount; ?></td>
                                                                                        <td><?= $fund->outstanding; ?></td>
                                                                                        <td><?= Html::a('<i class="fa fa-pencil"></i>', ['/funding/funding-allocation/add', 'id' => $id, 'fund_id' => $fund->id], ['class' => '', 'tittle' => 'Edit']) ?></td>

                                                                                </tr>
                                                                                <?php
                                                                                if ($fund->type == 3) {
                                                                                        $flag = 1;
                                                                                        $epda_outstanding = $fund->outstanding;
                                                                                }
                                                                                if ($fund->type == 4) {
                                                                                        $flag = 2;
                                                                                        $fda_outstanding = $fund->outstanding;
                                                                                }
                                                                                $totalamount += $fund->amount;
                                                                                ?>

                                                                                <?php
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                                <td colspan="6">Total</td>
                                                                                <td><?= $totalamount ?></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                                <?php
                                                                                if ($flag == 1) {
                                                                                        $epda_outstanding = $epda_outstanding - $totalamount;
                                                                                        ?>
                                                                                        <td colspan="7">Balance Outstanding After EPDA</td>
                                                                                        <td><?= $epda_outstanding ?></td>
                                                                                        <?php
                                                                                } elseif ($flag == 2) {
                                                                                        $fda_outstanding = $fda_outstanding - $totalamount;
                                                                                        ?>
                                                                                        <td colspan="7">Balance Outstanding After FDA</td>
                                                                                        <td><?= $fda_outstanding ?></td>
                                                                                        <?php
                                                                                } else {
                                                                                        ?>
                                                                                        <td colspan="7">Balance Outstanding</td>
                                                                                        <td><?= $totalamount ?></td>
                                                                                        <?php
                                                                                }
                                                                                ?>
                                                                                <td></td>
                                                                        </tr>
                                                        </table>
                                                        <?php
                                                }
                                        }
                                        ?>
                                </div>

                                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                        <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                        <tr>
                                                                <!--<th data-priority="1">#</th>-->
                                                                <!--<th data-priority="3">Appointment ID</th>-->
                                                                <th data-priority="3">Principal</th>
                                                                <!--<th data-priority="6" >Payment Mode</th>-->
                                                                <th data-priority="6" >Description</th>
                                                                <th data-priority="6">Payment Date</th>
                                                                <th data-priority="6">Payment Type</th>
                                                                <th data-priority="6">Check Number</th>
                                                                <th data-priority="6">Amount</th>
                                                                <th data-priority="6">Outstanding</th>
                                                                <th data-priority="6">From On Account</th>
                                                                <th data-priority="1">ACTIONS</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <tr class="filter">
                                                                <?php $form = ActiveForm::begin(); ?>
                                                                <!--<td></td>-->
                                                                <?php
                                                                $arr1 = explode(',', $appointment->principal);
                                                                if (count($arr1) == 1) {
                                                                        foreach ($arr1 as $value) {
                                                                                ?>
                                                                                <td><div class="form-group field-fundingallocation-principal_id">

                                                                                                <select id="fundingallocation-principal_id" class="form-control" name="FundingAllocation[principal_id]">
                                                                                                        <option value="<?= $value ?>"><?= $appointment->getClintCode($value); ?></option>
                                                                                                </select>

                                                                                                <div class="help-block"></div>
                                                                                        </div></td>
                                                                                <?php
                                                                        }
                                                                } else {
                                                                        ?>
                                                                        <td><?= $form->field($model, 'principal_id')->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1, 'id' => explode(',', $appointment->principal)]), 'id', 'principal_id'), ['prompt' => '-Principal-'])->label(false); ?></td>
                                                                        <?php
                                                                }
                                                                ?>
                                                                <td><?= $form->field($model, 'description')->textInput(['placeholder' => 'Description'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'fund_date')->textInput(['placeholder' => 'Date'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'payment_type')->dropDownList(['1' => 'Cash', '2' => 'Check'], ['prompt' => '-Payment Type-'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'check_no')->textInput(['placeholder' => 'Check Number'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'amount')->textInput(['placeholder' => 'Amount'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'outstanding')->textInput(['placeholder' => 'Outstanding'])->label(false) ?></td>
                                                                <td>
                                                                        <div class="form-group">
                                                                                <input type="checkbox" id="queue-order" name="check" value="1" checked="checked" uncheckValue="0"><label>On Account</label>
                                                                        </div>
                                                                </td>
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

                                <script>
                                        $("document").ready(function () {
                                                $('#subservices-service_id').change(function () {
                                                        var service_id = $(this).val();
                                                        $.ajax({
                                                                type: 'POST',
                                                                cache: false,
                                                                data: {service_id: service_id},
                                                                url: '<?= Yii::$app->homeUrl; ?>/appointment/estimated-proforma/subservice',
                                                                success: function (data) {
                                                                        $('#subservices-sub_service').html(data);
                                                                }
                                                        });
                                                });

                                        });
                                </script>
                                <script type="text/javascript">
                                        jQuery(document).ready(function ($)
                                        {
                                                $("#closeestimatesubservice-sub_service").select2({
                                                        //placeholder: 'Select your country...',
                                                        allowClear: true
                                                }).on('select2-open', function ()
                                                {
                                                        // Adding Custom Scrollbar
                                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                                });
                                                $("#closeestimatesubservice-sub_service").select2({
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
                                                $("#closeestimatesubservice-unit").keyup(function () {
                                                        multiply();
                                                });
                                                $("#closeestimatesubservice-unit_price").keyup(function () {
                                                        multiply();
                                                });
                                        });
                                        function multiply() {
                                                var rate = $("#closeestimatesubservice-unit").val();
                                                var unit = $("#closeestimatesubservice-unit_price").val();
                                                if (rate != '' && unit != '') {
                                                        $("#closeestimatesubservice-total").val(rate * unit);
                                                }

                                        }
                                        $("#closeestimatesubservice-total").prop("disabled", true);
                                        $("#fundingallocation-check_no").prop("disabled", true);
                                        $('#fundingallocation-payment_type').change(function () {
                                                var payment_id = $(this).val();
                                                if (payment_id == 2) {
                                                        $("#fundingallocation-check_no").prop("disabled", false);
                                                } else {
                                                        $("#fundingallocation-check_no").prop("disabled", true);
                                                }
                                        });
                                </script>
                        </div>
                        <?php //Pjax::end();          ?>
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
