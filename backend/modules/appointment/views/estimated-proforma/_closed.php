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
                                <div>
                                        <h2 style="text-align: center;color: red;">Appointment Closed</h2>
                                </div>
                                <hr class="appoint_history" />
                                <script>
                                        $(document).ready(function () {
                                                $('#epda-form').submit(function (e) {
                                                        var val = $('#epda_princi').val();
                                                        if (val != '') {
                                                                window.open('about:blank', 'print_popup', 'width=1200,height=500');
                                                        } else {
                                                                alert('please choose a Principal');
                                                                e.preventDefault();
                                                                return false;
                                                        }

                                                });
                                        });
                                </script>
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
                                                                                <!--<td><?php // $estimate->images;                                                                ?></td>-->
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
                                        url: '<?= Yii::$app->homeUrl; ?>/appointment/estimated-proforma/edit-estimate-service',
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
