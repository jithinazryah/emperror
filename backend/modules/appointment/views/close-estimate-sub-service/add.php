<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Services;
use common\models\MasterSubService;
use common\models\Currency;
use common\models\Contacts;
use common\models\Debtor;
use common\models\Appointment;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\components\AppointmentWidget;

/* @var $this yii\web\View */
/* @var $model common\models\EstimatedProforma */

$this->title = 'Create Close Estimate Sub Services';
$this->params['breadcrumbs'][] = ['label' => 'Close Estimate', 'url' => ['index']];
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
            <?php //Pjax::begin();    ?> 
            <div class="panel-body">
                <?= AppointmentWidget::widget(['id' => $appointment->id]) ?>

                <hr class="appoint_history" />

                <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                    <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="3">SUB SERVICE</th>
                                <th data-priority="3">UNIT</th>
                                <th data-priority="6" >UNIT PRICE</th>
                                <th data-priority="6">TOTAL</th>
                                <th data-priority="6">COMMENTS</th>
                                <th data-priority="1">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //var_dump($subcat);exit;
                            $i = 0;
                            foreach ($subcat as $sub):
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $sub->sub->sub_service; ?></td>
                                        <td><?= $sub->unit; ?></td>
                                        <td><?= $sub->unit_price; ?></td>
                                        <td><?= $sub->total; ?></td>
                                        <td><?= $sub->comments; ?></td>
                                        <td>
                                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/appointment/close-estimate-sub-service/add', 'id' => $sub->close_estimate_id, 'sub_id' => $sub->id], ['class' => '']) ?>
                                            <?= Html::a('<i class="fa fa-remove"></i>', ['/appointment/close-estimate-sub-service/delete-sub', 'id' => $sub->id], ['class' => '']) ?>
                                        </td>
                                        <?php
                                        $subtotal += $subcate->total;
                                        ?>
                                    </tr>
                                    <?php
                            endforeach;
                            ?>
                            <tr class="filter">
                                <?php $form = ActiveForm::begin(); ?>
                                <td></td>
                                <td><?= $form->field($model, 'sub_service')->dropDownList(ArrayHelper::map(MasterSubService::findAll(['status' => 1]), 'id', 'sub_service'), ['prompt' => '- Sub Service-'])->label(false); ?></td>
                                <td><?= $form->field($model, 'unit')->textInput(['placeholder' => 'Unit'])->label(false) ?></td>
                                <td><?= $form->field($model, 'unit_price')->textInput(['placeholder' => 'Unit Price'])->label(false) ?></td>
                                <td><?= $form->field($model, 'total')->textInput(['placeholder' => 'Total', 'disabled' => true])->label(false) ?></td>
                                <td><?= $form->field($model, 'comments')->textInput(['placeholder' => 'Comments'])->label(false) ?></td>
                                <td><?= Html::submitButton($model->isNewRecord ? 'Add' : 'Update', ['class' => 'btn btn-success']) ?>
                                </td>
                                <?php ActiveForm::end(); ?>
                            </tr>

                        </tbody>
                    </table>
                    <div>
                        <?php
                        echo Html::a('<span>Back to Close Estimate</span>', ['/appointment/close-estimate/add', 'id' => $appointment->id], ['class' => 'btn btn-secondary']);
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
                </script>
            </div>
            <?php //Pjax::end();    ?> 
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
