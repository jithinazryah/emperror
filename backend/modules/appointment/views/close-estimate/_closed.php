<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Services;
use common\models\Currency;
use common\models\Contacts;
use common\models\CloseEstimate;
use common\models\Debtor;
use common\models\InvoiceType;
use common\models\Vessel;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\components\AppointmentWidget;

/* @var $this yii\web\View */
/* @var $model common\models\EstimatedProforma */

$this->title = 'Create Close Estimte';
$this->params['breadcrumbs'][] = ['label' => 'Close Estimte', 'url' => ['index']];
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
                                <div>
                                        <h2 style="text-align: center;color: red;">Appointment Closed</h2>
                                </div>
                                <hr class="appoint_history" />
                                <ul class="estimat nav nav-tabs nav-tabs-justified">
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Appointment</span>', ['appointment/update', 'id' => $appointment->id]);
                                                ?>

                                        </li>
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Estimated Proforma</span>', ['estimated-proforma/add', 'id' => $appointment->id]);
                                                ?>

                                        </li>
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Port call Data</span>', ['port-call-data/update', 'id' => $appointment->id]);
                                                ?>

                                        </li>
                                        <li class="active">
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
                                                                        <th data-priority="6">EPDA VALUE</th>
                                                                        <th data-priority="6">FDA VALUE</th>
                                                                        <th data-priority="6">PAYMENT TYPE</th>
                                                                        <th data-priority="6">TOTAL</th>
                                                                        <th data-priority="6">INVOICE TYPE</th>
                                                                        <th data-priority="6">PRINCIPAL</th>
                                                                        <th data-priority="6">COMMENTS</th>
                                                                </tr>
                                                        </thead>

                                                        <tbody>
                                                                <?php
                                                                $i = 0;
                                                                $grandtotal = 0;
                                                                $epdatotal = 0;
                                                                $fdatotal = 0;
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
                                                                                <td><?= $estimate->fda; ?></td>
                                                                                <?php
                                                                                if ($estimate->payment_type == 1) {
                                                                                        $payment_type = 'Manual';
                                                                                } elseif ($estimate->payment_type == 2) {
                                                                                        $payment_type = 'Check';
                                                                                } else {
                                                                                        $payment_type = '';
                                                                                }
                                                                                ?>
                                                                                <td><?= $payment_type; ?></td>
                                                                                <td><?= $estimate->total; ?></td>
                                                                                <td><?= $estimate->invoice->invoice_type ?></td>
                                                                                <td><?= $estimate->principal0->principal_name; ?></td>
                                                                                <td><?= $estimate->comments; ?></td>
                                                                                <?php
                                                                                $epdatotal += $estimate->epda;
                                                                                $fdatotal += $estimate->fda;
                                                                                $grandtotal += $estimate->total;
                                                                                ?>
                                                                        </tr>

                                                                        <?php
                                                                endforeach;
                                                                ?>
                                                                <tr>
                                                                        <td></td>
                                                                        <td colspan="4"> <b>GRAND TOTAL</b></td>
                                                                        <td style="font-weight: bold;"><?php echo $epdatotal . '/-'; ?></td>
                                                                        <td style="font-weight: bold;"><?php echo $fdatotal . '/-'; ?></td>
                                                                        <td></td>
                                                                        <td style="font-weight: bold;"><?php echo $grandtotal . '/-'; ?></td>
                                                                        <td colspan=""></td>
                                                                </tr>
                                                                <tr></tr>

                                                                <!-- Repeat -->

                                                        </tbody>

                                                </table>
                                                <br/>
                                        </div>
                                </div>
                                <script>
                                        $("document").ready(function () {
                                                $('#closeestimate-service_id').change(function () {
                                                        var service_id = $(this).val();
                                                        $.ajax({
                                                                type: 'POST',
                                                                cache: false,
                                                                data: {service_id: service_id},
                                                                url: '<?= Yii::$app->homeUrl; ?>/appointment/close-estimate/supplier',
                                                                success: function (data) {
                                                                        if (data == 1) {
                                                                                $("#closeestimate-supplier").prop('disabled', false);
                                                                        } else {
                                                                                $("#closeestimate-supplier").prop('disabled', true);
                                                                        }
                                                                }
                                                        });
                                                });

                                        });
                                </script>
                                <script type="text/javascript">
                                        jQuery(document).ready(function ($)
                                        {
                                                $("#closeestimate-service_id").select2({
                                                        //placeholder: 'Select your country...',
                                                        allowClear: true
                                                }).on('select2-open', function ()
                                                {
                                                        // Adding Custom Scrollbar
                                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                                });



                                                $("#closeestimate-supplier").select2({
                                                        //placeholder: 'Select your country...',
                                                        allowClear: true
                                                }).on('select2-open', function ()
                                                {
                                                        // Adding Custom Scrollbar
                                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                                });

                                                $("#estimatedproforma-currency").select2({
                                                        //placeholder: 'Select your country...',
                                                        allowClear: true
                                                }).on('select2-open', function ()
                                                {
                                                        // Adding Custom Scrollbar
                                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                                });


                                                $("#closeestimate-principal").select2({
                                                        //placeholder: 'Select your country...',
                                                        allowClear: true
                                                }).on('select2-open', function ()
                                                {
                                                        // Adding Custom Scrollbar
                                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                                });



                                        });
                                </script>

                                <script>
                                        $(document).ready(function () {
                                                $("#closeestimate-unit_rate").keyup(function () {
                                                        multiply();
                                                });
                                                $("#closeestimate-unit").keyup(function () {
                                                        multiply();
                                                });
                                        });
                                        function multiply() {
                                                var rate = $("#closeestimate-unit_rate").val();
                                                var unit = $("#closeestimate-unit").val();
                                                if (rate != '' && unit != '') {
                                                        $("#closeestimate-epda").val(rate * unit);
                                                }

                                        }
                                        $("#closeestimate-epda").prop("disabled", true);
                                </script>


                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                        </div>
                        <?php //Pjax::end();          ?>
                </div>
        </div>
        <style>
                .filter{
                        background-color: #b9c7a7;
                }
                table.table tr td:last-child a {
                        padding: 0px 4px;
                }
                .principp{
                        display:none;
                }
        </style>
</div>