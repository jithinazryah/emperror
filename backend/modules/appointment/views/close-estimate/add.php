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
                <!--                <div style="float: left;">
                <?php
//                    echo Html::a('<i class="fa-print"></i><span>Generate Report</span>', ['close-estimate/report', 'id' => $appointment->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']);
                ?>
                                </div>-->


                <div class="col-md-12" style="float: left;">
                    <div class="row">
                    <?php
                    $arr = CloseEstimate::find()->select('invoice_type')->distinct()->where(['apponitment_id' => $appointment->id])->all();
                    //echo count($arr);exit;
                    foreach ($arr as $value) {
                            if ($value->invoice_type != '') {
                                    $invoice_type = $value->invoice_type;
                            }
                    }
                    //if ($invoice_type != '') {
                    ?>
                    <?= Html::beginForm(['close-estimate/report'], 'post', ['target' => 'print_popup', 'onSubmit' => "window.open('about:blank','print_popup','width=1200,height=600');"]) ?>
                    <?php
                    //if (count($arr) != 0) {
                    if (count($arr) == 1) {
                            ?>
                                <?php
                                $arr = CloseEstimate::find()->select('invoice_type')->distinct()->where(['apponitment_id' => $appointment->id])->one();
                                ?>
                                <input type="hidden" name="app_id" value="<?= $appointment->id ?>">
                                <?php
//                                foreach ($arr as $value) {
//                                        if ($value->invoice_type != '') {
//                                                ?>
                                                <input type="hidden" name="invoice_type" value="//<?php// $arr->invoice_type ?>">
                                                <?php
//                                        }
//                                }
                                ?>

                                <?php
                        } else {
                                ?>

                                <input type="hidden" name="app_id" value="<?= $appointment->id ?>">


                                <?php
                        }
                        //}
                        ?>
                        <div class="col-md-4">
                            <select name = "invoice_type" id = "close-estimate-invoice" class="form-control">
                                <option selected = "selected">Select Invoice Type</option>
                                <option value="all">All</option>
                                <?php
                                foreach ($arr as $key => $value) {
                                        if ($value->invoice_type != '') {
                                                $data = InvoiceType::findOne(['id' => $value->invoice_type]);
                                                ?>
                                                <option value="<?= $value->invoice_type ?>"><?= $data->invoice_type ?></option>
                                                <?php
                                        }
                                }
                                ?>
                            </select> 
                        </div>
                        <div class="col-md-4 principp">
                            <?php
                            $principals = CloseEstimate::find()->select('principal')->distinct()->where(['apponitment_id' => $appointment->id])->all();
                            //$principals = explode(',', $appointment->principal);
                            if (count($principals) > 1) {
                                    ?>
                                    <div>
                                        <select name = "fda" id = "fda" class="form-control">
                                            <option value="" selected = "selected">Select Principal</option>
                                            <?php
                                            foreach ($principals as $princippp) {
                                                    $data = Debtor::findOne(['id' => $princippp->principal]);
                                                    ?>
                                                    <option value="<?= $princippp->principal ?>"><?= $data->principal_name ?></option>
                                            <?php }
                                            ?>
                                        </select>  
                                    </div>
                                    <?php
                            } else {
                                    foreach ($principals as $princippp) {
                                            ?>
                                            <input type="hidden" name="fda" value="<?= $princippp->principal ?>">
                                            <?php
                                    }
                            }
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::submitButton('<i class="fa-print"></i><span>Generate Final DA</span>', ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
   <!--<input type="submit" name="b1" value="Submit">-->
                            <?= Html::endForm() ?>
                            <?php
//                    echo Html::a('<i class="fa-print"></i><span>Generate Report</span>', ['estimated-proforma/report', 'id' => $appointment->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']);
                            ?> 
                        </div>   
                    </div>
                    <?php
                    //}
                    ?>
                </div>
                <?php
                if (empty($estimates)) {
                        ?>
                        <div style="float:left;margin-right: 10px;">
                            <?php
                            echo Html::a('<span>Load Estimated Proforma</span>', ['close-estimate/insert-close-estimate', 'id' => $appointment->id], ['class' => 'btn btn-secondary']);
                            ?>      

                        </div>
                        <?php
                }
                ?>
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
                                    <th data-priority="1">ACTIONS</th>
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
                                            <td>
                                                <?= Html::a('<i class="fa fa-pencil"></i>', ['/appointment/close-estimate/add', 'id' => $id, 'prfrma_id' => $estimate->id], ['class' => 'btn btn-icon btn-primary']) ?>
                                                <?= Html::a('<i class="fa-remove"></i>', ['/appointment/close-estimate/delete-close-estimate', 'id' => $estimate->id], ['class' => 'btn btn-icon btn-red', 'data-confirm' => 'Are you sure you want to delete this item?']) ?>
                                                <?= Html::a('<i class="fa fa-database"></i>', ['/appointment/close-estimate-sub-service/add', 'id' => $estimate->id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>
                                            </td>
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
                                    <td colspan=""></td>
                                </tr>
                                <tr class="formm">
                                    <?php $form = ActiveForm::begin(); ?>
                                    <td></td>
                                    <td><?= $form->field($model, 'service_id')->dropDownList(ArrayHelper::map(Services::findAll(['status' => 1]), 'id', 'service'), ['prompt' => '-Service-'])->label(false); ?></td>
                                    <td><?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 4])->all(), 'id', 'name'), ['prompt' => '-Supplier-'])->label(false); ?></td>
    <!--                                <td><?php // $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Contacts::findAll(['status' => 1]), 'id', 'name'), ['prompt' => '-Supplier-'])->label(false);                                   ?></td>-->
    <!--                                                                <td><?php // $form->field($model, 'currency')->dropDownList(ArrayHelper::map(Currency::findAll(['status' => 1]), 'id', 'currency_name'), ['prompt' => '-Currency-'])->label(false);                                        ?></td>-->
                                    <td><?= $form->field($model, 'unit_rate')->textInput(['placeholder' => 'Unit Rate'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'unit')->textInput(['placeholder' => 'Quantity'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'epda')->textInput(['placeholder' => 'EPDA'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'fda')->textInput(['placeholder' => 'FDA'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'payment_type')->dropDownList(['1' => 'Manual', '2' => 'Check'], ['prompt' => '-Payment Type-'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'total')->textInput(['placeholder' => 'TOTAL'])->label(false) ?></td>
                                    <td><?= $form->field($model, 'invoice_type')->dropDownList(ArrayHelper::map(InvoiceType::findAll(['status' => 1]), 'id', 'invoice_type'), ['prompt' => '-Invoice Type-'])->label(false); ?></td>
                                    <td><?= $form->field($model, 'principal')->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1, 'id' => explode(',', $appointment->principal)]), 'id', 'principal_name'), ['prompt' => '-Principal-'])->label(false); ?></td>
                                    <td><?= $form->field($model, 'comments')->textInput(['placeholder' => 'Comments'])->label(false) ?></td>
                                    <td><?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => 'btn btn-success']) ?>
                                    </td>
                                    <?php ActiveForm::end(); ?>
                                </tr>
                                <tr></tr>

                                <!-- Repeat -->

                            </tbody>

                        </table>
                        <br/>
                        <hr class="appoint_history" />
                        <div style="text-align: center;">
                            <h4 class="sub-heading">Uploaded Files</h4>
                            <br/>
                            <?php
                            if (!empty(Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['closePath']))) {
                                    ?>
                                    <span class="upload_file_list"><?= Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['closePath']); ?></span>
                                    <?php
                            }
                            ?>
                        </div>
                        <hr class="appoint_history" />
                        <div style="float: left;margin-left: 46%;">
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => Yii::$app->homeUrl . 'appointment/close-estimate/uploads', 'method' => 'post']) ?>
                            <?php
                            $model_upload->appointment_id = $appointment->id;
                            $model_upload->type = Yii::$app->params['closePath'];
                            ?>
                            <?= $form->field($model_upload, 'filee[]')->fileInput(['multiple' => true]) ?>
                            <?= $form->field($model_upload, 'appointment_id')->hiddenInput()->label(false) ?>
                            <?= $form->field($model_upload, 'type')->hiddenInput()->label(false) ?>
                            <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>


                            <?php ActiveForm::end() ?>  
                        </div>
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
            <?php //Pjax::end();         ?> 
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
    <script>
                        $("document").ready(function () {
                            $('#close-estimate-invoice').change(function () {
                                var invoice = $(this).val();
                                if (invoice == 'all') {
                                    $('.principp').show();
                                }
                                else {
                                    $('.principp').hide();
                                }
                            });

                        });
    </script>
</div>