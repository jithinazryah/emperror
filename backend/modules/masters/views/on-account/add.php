<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Debtor;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\components\AppointmentWidget;
use common\models\OnAccount;

/* @var $this yii\web\View */
/* @var $model common\models\EstimatedProforma */

$this->title = 'On Account';
$this->params['breadcrumbs'][] = ['label' => ' On Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
        <div class="col-md-12">

                <div class="panel panel-default">
                        <div class="panel-heading">
                                <h2  class="appoint-title panel-title"><?= Html::encode($this->title) ?></h2>

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

                                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                        <?php
                                        if (!empty($on_accounts)) {
                                                ?>
                                                <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                                        <thead>
                                                                <tr>
                                                                        <th>#</th>
                                                                        <th>Debtor</th>
                                                                        <th>Appointment</th>
                                                                        <th>Transaction Type</th>
                                                                        <th>Payment_type</th>
                                                                        <th>Check No</th>
                                                                        <th>Amount</th>
                                                                        <th>Balance</th>
                                                                        <th>Date</th>
                                                                        <th>Comment</th>
                                                                        <th data-priority="1">ACTIONS</th>
                                                                </tr>
                                                        </thead>
                                                        <tbody>
                                                                <?php
                                                                $j = 0;
                                                                foreach ($on_accounts as $on_account) {
                                                                        $j++;
                                                                        ?>

                                                                        <tr class="filter">
                                                                                <td><?= $j; ?></td>
                                                                                <td><?= Debtor::findOne($on_account->debtor_id)->principal_id; ?></td>
                                                                                <td><?= $on_account->appointment_id; ?></td>
                                                                                <?php
                                                                                if ($on_account->transaction_type == 1) {
                                                                                        $tracsaction_type = 'Credit';
                                                                                } elseif ($on_account->transaction_type == 2) {
                                                                                        $tracsaction_type = 'Debit';
                                                                                }
                                                                                ?>
                                                                                <td><?= $tracsaction_type; ?></td>
                                                                                <?php
                                                                                if ($on_account->payment_type == 1) {
                                                                                        $payment_type = 'Cash';
                                                                                } elseif ($on_account->payment_type == 2) {
                                                                                        $payment_type = 'Check';
                                                                                }
                                                                                ?>
                                                                                <td><?= $payment_type; ?></td>
                                                                                <td><?= $on_account->check_no; ?></td>
                                                                                <td><?= $on_account->amount; ?></td>
                                                                                <td><?= $on_account->balance; ?></td>
                                                                                <td><?= Yii::$app->SetValues->DateFormate($on_account->date); ?></td>
                                                                                <td><?= $on_account->comment; ?></td>
                                                                                <td><?= Html::a('<i class="fa fa-pencil"></i>', ['/masters/on-account/add', 'id' => $id, 'fund_id' => $on_account->id], ['class' => '', 'tittle' => 'Edit']) ?></td>

                                                                        </tr>

                                                                        <?php
                                                                }
                                                                ?>
                                                        </tbody>
                                                </table>
                                                <?php
                                        }
                                        ?>
                                </div>

                                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                        <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                        <tr>
                                                                <th data-priority="3">Payment_type</th>
                                                                <th data-priority="6" >Check No</th>
                                                                <th data-priority="6">Amount</th>
                                                                <th data-priority="6">Date</th>
                                                                <th data-priority="6">Comments</th>
                                                                <th data-priority="6">Status</th>
                                                                <th data-priority="1">ACTIONS</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <tr class="filter">
                                                                <?php $form = ActiveForm::begin(); ?>
                                                                <td><?= $form->field($model, 'payment_type')->dropDownList(['1' => 'Cash', '2' => 'Check'], ['prompt' => '-Payment Type-'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'check_no')->textInput(['placeholder' => 'Check Number'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'amount')->textInput(['placeholder' => 'Amount'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'date')->textInput(['placeholder' => 'Date'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'comment')->textInput(['placeholder' => 'Comments'])->label(false) ?></td>
                                                                <td><?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '2' => 'Disabled'], ['prompt' => '-Status-'])->label(false) ?></td>
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



                                <script type="text/javascript">
                                        jQuery(document).ready(function ($)
                                        {
                                                $("#actualfunding-service_id").prop("disabled", true);

                                                $("#actualfunding-fda_amount").prop("disabled", true);

                                                $("#actualfunding-amount_difference").prop("disabled", true);

                                        });</script>


                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>

                        </div>
                        <?php //Pjax::end();            ?>
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
        </style>
</div>
<script>
                                        $(document).ready(function () {
                                                $("#onaccount-check_no").prop("disabled", true);
                                                $('#onaccount-payment_type').change(function () {
                                                        var payment_id = $(this).val();
                                                        if (payment_id == 2) {
                                                                $("#onaccount-check_no").prop("disabled", false);
                                                        } else {
                                                                $("#onaccount-check_no").prop("disabled", true);
                                                        }
                                                });
                                        });
</script>