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
use common\models\InvoiceNumber;
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
                                                foreach ($arr as $value) {
                                                        if ($value->invoice_type != '') {
                                                                $invoice_type = $value->invoice_type;
                                                        }
                                                }
                                                ?>
                                                <?= Html::beginForm(['close-estimate/report'], 'post', ['target' => 'print_popup', 'onSubmit' => "window.open('about:blank','print_popup','width=1200,height=600');"]) ?>
                                                <?php
                                                if (count($arr) == 1) {
                                                        ?>
                                                        <?php
                                                        $arr = CloseEstimate::find()->select('invoice_type')->distinct()->where(['apponitment_id' => $appointment->id])->one();
                                                        ?>
                                                        <input type="hidden" name="app_id" value="<?= $appointment->id ?>">
                                                        <?php //                                                ?>
                                                        <input type="hidden" name="invoice_type" value="//<?php // $arr->invoice_type                                                                                                                                                       ?>">
                                                        <?php
                                                        ?>

                                                        <?php
                                                } else {
                                                        ?>

                                                        <input type="hidden" name="app_id" value="<?= $appointment->id ?>">


                                                        <?php
                                                }
                                                ?>
                                                <div class="col-md-3">
                                                        <input type="text" name="invoice_date" value="" class="form-control" placeholder="Invoice Date"/>
                                                </div>
                                                <div class="col-md-3">
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



                                                <?php
                                                $principals = CloseEstimate::find()->select('principal')->distinct()->where(['apponitment_id' => $appointment->id])->all();
                                                if (count($principals) > 1) {
                                                        ?>
                                                        <div class="col-md-3 principp">

                                                                <select name = "fda" id = "fda" class="form-control">
                                                                        <option value="" selected = "selected">Select Principal</option>
                                                                        <?php
                                                                        foreach ($principals as $princippp) {
                                                                                if ($princippp->principal != '') {
                                                                                        $data = Debtor::findOne(['id' => $princippp->principal]);
                                                                                        ?>
                                                                                        <option value="<?= $princippp->principal ?>"><?= $data->principal_name ?></option>
                                                                                        <?php
                                                                                }
                                                                        }
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
                                                <div class="col-md-3">
                                                        <?= Html::submitButton('<i class="fa-print"></i><span>Generate Final DA</span>', ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
                                                        <?= Html::endForm() ?>
                                                        <?php
                                                        ?>
                                                </div>
                                        </div>
                                        <?php
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
                                                                        <!--<th data-priority="6">PAYMENT TYPE</th>-->
                                                                        <!--<th data-priority="6">TOTAL</th>-->
                                                                        <th data-priority="6">INVOICE TYPE</th>
                                                                        <th data-priority="6">PRINCIPAL</th>
                                                                        <th data-priority="6">COMMENTS TO EPDA</th>
                                                                        <th data-priority="6">COMMENTS TO FDA</th>
                                                                        <th data-priority="1">ACTIONS</th>
                                                                        <th data-priority="1">PRINT</th>
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
                                                                                <td><span class="" drop_id="closeestimate-service_id" id="<?= $estimate->id ?>-service_id" val="<?= $estimate->service_id ?>"><?= $estimate->service->service ?></span></td>
                                                                                <td><span class="" drop_id="closeestimate-supplier" id="<?= $estimate->id ?>-supplier" val="<?= $estimate->supplier ?>"><?= $estimate->supplier0->name ?></span></td>
                                                                                <td><span class="" id="<?= $estimate->id ?>-unit_rate"  val="<?= $estimate->unit_rate ?>">
                                                                                                <?php
                                                                                                if ($estimate->unit_rate == '') {
                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                                                } else {
                                                                                                        echo Yii::$app->SetValues->NumberFormat($estimate->unit_rate);
                                                                                                }
                                                                                                ?>
                                                                                        </span>
                                                                                </td>
                                                                                <td><span class="" id="<?= $estimate->id ?>-unit" val="<?= $estimate->unit ?>">
                                                                                                <?php
                                                                                                if ($estimate->unit == '') {
                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                                                } else {
                                                                                                        echo Yii::$app->SetValues->NumberFormat($estimate->unit);
                                                                                                }
                                                                                                ?>
                                                                                        </span>
                                                                                </td>
                                                                                <td><span class="" id="<?= $estimate->id ?>-epda" val="<?= $estimate->epda ?>"><?= Yii::$app->SetValues->NumberFormat($estimate->epda); ?></span></td>
                                                                                <td><span class="edit_text" id="<?= $estimate->id ?>-fda" val="<?= $estimate->fda ?>">
                                                                                                <?php
                                                                                                if ($estimate->fda == '') {
                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                                                } else {
                                                                                                        echo Yii::$app->SetValues->NumberFormat($estimate->fda);
                                                                                                }
                                                                                                ?>
                                                                                        </span>
                                                                                </td>
                                                                                <?php
//                                                                                if ($estimate->payment_type == 1) {
//                                                                                        $payment_type = 'Manual';
//                                                                                } elseif ($estimate->payment_type == 2) {
//                                                                                        $payment_type = 'Check';
//                                                                                } else {
//                                                                                        $payment_type = '';
//                                                                                }
                                                                                ?>
        <!--                                                                                <td><span class="edit_dropdown"  drop_id="closeestimate-payment_type" id="<?= $estimate->id ?>-payment_type" val="<?= $estimate->payment_type ?>">
                                                                                <?php
//                                                                                                if ($estimate->payment_type == '') {
//                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
//                                                                                                } else {
//                                                                                                        echo $payment_type;
//                                                                                                }
                                                                                ?>
                                                                                        </span>
                                                                                </td>-->
                                                                                <!--<td><?php // $estimate->total;                                                                                                                                                      ?></td>-->
                                                                                <td><span class="edit_dropdown" drop_id="closeestimate-invoice_type" id="<?= $estimate->id ?>-invoice_type" val="<?= $estimate->invoice_type ?>">
                                                                                                <?php
                                                                                                if ($estimate->invoice_type == '') {
                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                                                } else {
                                                                                                        echo $estimate->invoice->invoice_type;
                                                                                                }
                                                                                                ?>
                                                                                        </span>
                                                                                </td>
                                                                                <td><span class="edit_dropdown" drop_id="closeestimate-principal" id="<?= $estimate->id ?>-principal" val="<?= $estimate->principal ?>">
                                                                                                <?php
                                                                                                if ($estimate->principal == '') {
                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                                                } else {
                                                                                                        echo $estimate->principal0->principal_id;
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

                                                                                        </span></td>
                                                                                <td><span class="edit_text" id="<?= $estimate->id ?>-comment_to_fda" val="<?= $estimate->comment_to_fda ?>">

                                                                                                <?php
                                                                                                if ($estimate->comment_to_fda == '') {
                                                                                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                                                                } else {
                                                                                                        if (strlen($estimate->comment_to_fda) > 15) {
                                                                                                                echo substr($estimate->comment_to_fda, 0, 15) . '...';
                                                                                                        } else {
                                                                                                                echo $estimate->comment_to_fda;
                                                                                                        }
                                                                                                }
                                                                                                ?>

                                                                                        </span></td>
                                                                                <td>
                                                                                        <?= Html::a('<i class="fa fa-pencil"></i>', ['/appointment/close-estimate/add', 'id' => $id, 'prfrma_id' => $estimate->id], ['class' => '']) ?>
                                                                                        <?= Html::a('<i class="fa-remove"></i>', ['/appointment/close-estimate/delete-close-estimate', 'id' => $estimate->id], ['class' => '', 'data-confirm' => 'Are you sure you want to delete this item?']) ?>
                                                                                        <?= Html::a('<i class="fa fa-database"></i>', ['/appointment/close-estimate-sub-service/add', 'id' => $estimate->id], ['class' => '', 'target' => '_blank']) ?>
                                                                                        <?= Html::a('<i class="fa-print"></i>', ['close-estimate/fda-report'], ['class' => '', 'onclick' => "window.open('fda-report?id=$estimate->apponitment_id & estid=$estimate->id', 'newwindow', 'width=1200, height=500');return false;"]) ?>
                                                                                </td>
                                                                                <td>
                                                                                        <?= Html::beginForm(['close-estimate/selected-report'], 'post', ['target' => 'print_popup', 'onSubmit' => "window.open('about:blank','print_popup','width=1200,height=600');"]) ?>
                                                                                        <input type="checkbox" name="invoice_type[<?= $estimate->id ?>]" value="<?= $estimate->invoice_type ?>">
                                                                                        <input type="hidden" name="app_id" value="<?= $appointment->id ?>">
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
                                                                        <td style="font-weight: bold;"><?php echo Yii::$app->SetValues->NumberFormat($epdatotal) . '/-'; ?></td>
                                                                        <td style="font-weight: bold;"><?php echo Yii::$app->SetValues->NumberFormat($fdatotal) . '/-'; ?>
                                                                        <td></td>
                                                                        <!--<td style="font-weight: bold;"><?php //echo $grandtotal . '/-';                                                                                                                                                       ?></td>-->
                                                                        <td colspan=""></td>
                                                                        <td colspan=""></td>
                                                                        <td colspan=""></td>
                                                                        <td colspan=""></td>
                                                                        <td colspan="">
                                                                                <?= Html::submitButton('<i class="fa-print"></i><span>FDA</span>', ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
                                                                                <?= Html::endForm() ?>
                                                                        </td>
                                                                </tr>
                                                                <tr class="formm">
                                                                        <?php $form = ActiveForm::begin(); ?>
                                                                        <td></td>
                                                                        <td><?= $form->field($model, 'service_id')->dropDownList(ArrayHelper::map(Services::findAll(['status' => 1]), 'id', 'service'), ['prompt' => '-Service-'])->label(false); ?></td>
                                                                        <td><?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 4])->all(), 'id', 'name'), ['prompt' => '-Supplier-'])->label(false); ?></td>
                                        <!--                                <td><?php // $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Contacts::findAll(['status' => 1]), 'id', 'name'), ['prompt' => '-Supplier-'])->label(false);                                                                                                                                                                                          ?></td>-->
                                        <!--                                                                <td><?php // $form->field($model, 'currency')->dropDownList(ArrayHelper::map(Currency::findAll(['status' => 1]), 'id', 'currency_name'), ['prompt' => '-Currency-'])->label(false);                                                                                                                                                                                               ?></td>-->
                                                                        <td><?= $form->field($model, 'unit_rate')->textInput(['placeholder' => 'Unit Rate'])->label(false) ?></td>
                                                                        <td><?= $form->field($model, 'unit')->textInput(['placeholder' => 'Quantity'])->label(false) ?></td>
                                                                        <td><?= $form->field($model, 'epda')->textInput(['placeholder' => 'EPDA'])->label(false) ?></td>
                                                                        <td><?= $form->field($model, 'fda')->textInput(['placeholder' => 'FDA'])->label(false) ?></td>
                                                                        <!--<td><?php // $form->field($model, 'payment_type')->dropDownList(['1' => 'Manual', '2' => 'Check'], ['prompt' => '-Payment Type-'])->label(false)                                                                                      ?></td>-->
                                                                        <!--<td><?php // $form->field($model, 'total')->textInput(['placeholder' => 'TOTAL'])->label(false)                                                                                                                                                       ?></td>-->
                                                                        <td><?= $form->field($model, 'invoice_type')->dropDownList(ArrayHelper::map(InvoiceType::findAll(['status' => 1]), 'id', 'invoice_type'), ['prompt' => '-Invoice Type-'])->label(false); ?></td>
                                                                        <?php
                                                                        $arr1 = explode(',', $appointment->principal);
                                                                        if (count($arr1) == 1) {
                                                                                foreach ($arr1 as $value) {
                                                                                        ?>
                                                                                        <td><div class = "form-group field-closeestimate-principal">

                                                                                                        <select id = "closeestimate-principal" class = "form-control" name = "CloseEstimate[principal]">
                                                                                                                <option value = "<?= $value ?>"><?= $appointment->getClintCode($value); ?></option>
                                                                                                        </select>

                                                                                                        <div class = "help-block"></div>
                                                                                                </div>
                                                                                        </td>
                                                                                        <?php
                                                                                }
                                                                        } else {
                                                                                ?>
                                                                                <td><?= $form->field($model, 'principal')->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1, 'id' => explode(',', $appointment->principal)]), 'id', 'principal_id'), ['prompt' => '-Principal-'])->label(false); ?></td>
                                                                                <?php
                                                                        }
                                                                        ?>
                                                                        <td><?= $form->field($model, 'comments')->textInput(['placeholder' => 'EPDA Comments'])->label(false) ?></td>
                                                                        <td><?= $form->field($model, 'comment_to_fda')->textInput(['placeholder' => 'FDA Comments'])->label(false) ?></td>
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
                                                <div style="margin-left: 46%;">
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
                                                <br/>
                                                <hr class="appoint_history" />
                                                <div class="display-uploads" style="margin-bottom: 25px;">
                                                        <div class="row" style="display:inline-block">
                                                                <div class="col-md-4" style="float:left;text-align: center;">
                                                                        <table>
                                                                                <tr>
                                                                                        <td style="border: 1px solid black;"><h4 class="sub-heading">Previously Generated FDA'S</h4></td>
                                                                                </tr>
                                                                                <?php
                                                                                $estmate_reports = InvoiceNumber::findAll(['appointment_id' => $appointment->id]);
                                                                                ?>
                                                                                <?php foreach ($estmate_reports as $estmate_report) { ?>
                                                                                        <tr>
                                                                                                <td style="border: 1px solid black;padding: 10px;">
                                                                                                        <?php echo Html::a($estmate_report->date_time, ['/appointment/close-estimate/show-report'], ['onclick' => "window.open('show-report?id=$estmate_report->id', 'newwindow', 'width=750, height=500');return false;"]) . '&nbsp;&nbsp;<a href="remove-report?id=' . $estmate_report->id . '"><i class="fa fa-remove"></i></a>'; ?>
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
                                                                                if (!empty(Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['closePath']))) {
                                                                                        ?>
                                                                                        <tr>
                                                                                                <td style="border: 1px solid black;padding: 10px;">
                                                                                                        <?= Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['closePath']); ?>
                                                                                                </td>
                                                                                                <?php
                                                                                        }
                                                                                        ?>

                                                                        </table>
                                                                </div>
                                                        </div>

                                                </div>


                                                <!--                                                <div class="display-uploads">
                                                                                                        <div class="row" style="display:inline-block">
                                                                                                                <table>
                                                                                                                        <tr>
                                                                                                                                <td style="border: 1px solid black;"><h4 class="sub-heading">Uploaded Files</h4></td>
                                                                                                                        </tr>
                                                <?php
//                                                                        if (!empty(Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['closePath']))) {
                                                ?>
                                                                                                                                <tr>
                                                                                                                                        <td style="border: 1px solid black;padding: 10px;">
                                                <?php // Yii::$app->UploadFile->ListFile($appointment->id, Yii::$app->params['closePath']); ?>
                                                                                                                                        </td>
                                                                                                                                </tr>
                                                <?php
//                                                                        }
                                                ?>

                                                                                                                </table>

                                                                                                        </div>

                                                                                                </div>-->
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
                                                                $("#closeestimate-fda").val(rate * unit);
                                                        }

                                                }
                                                $("#closeestimate-fda").prop("disabled", true);
                                        </script>


                                        <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                        <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                        <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                                </div>
                                <?php //Pjax::end();               ?>
                        </div>
                </div>
        </div>
</div>
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
                        padding: 0px 4px;
                }
                .principp{
                        display:none;
                }
                .display-uploads{
                        margin-bottom: 25px;
                        text-align: center;
                        margin-left: 175px;
                        margin-right: 100px;
                }
                /*        .edit-input {
                            display:none;
                        }*/
        </style>
        <script>
                                                $("document").ready(function () {
                                                        $('#close-estimate-invoice').change(function () {
                                                                var invoice = $(this).val();
                                                                if (invoice == 'all') {
                                                                        $('.principp').show();
                                                                } else {
                                                                        $('.principp').hide();
                                                                }
                                                        });
                                                });
        </script>
        <script>
                $("document").ready(function () {


                        /*
                         * Double click enter function
                         * */

                        $('.edit_text').on('dblclick', function () {
                                var val = $(this).attr('val');
                                var idd = this.id;
                                var res_data = idd.split("-");
                                if (res_data[1] == 'comments' || res_data[1] == 'comment_to_fda') {
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
                                        url: '<?= Yii::$app->homeUrl; ?>/appointment/close-estimate/edit-estimate',
                                        success: function (data) {
                                                if (data == '') {
                                                        data = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                }
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
                                        url: '<?= Yii::$app->homeUrl; ?>/appointment/close-estimate/edit-estimate-service',
                                        success: function (data) {
                                                if (data == '') {
                                                        data = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                }
                                                thiss.html(data);
                                        }
                                });

                        });


                });
        </script>
</div>
