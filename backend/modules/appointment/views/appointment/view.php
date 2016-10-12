<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use common\models\VesselType;
use common\models\Vessel;
use common\models\Ports;
use common\models\Services;
use common\models\Currency;
use common\models\Terminal;
use common\models\Debtor;
use common\models\Contacts;
use common\models\Purpose;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Appointment */

$this->title = $model->appointment_no;
$this->params['breadcrumbs'][] = ['label' => 'Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" style="background : #e6e6e6">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>

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
            <div class="panel-body">
                <?= Html::a('<i class="fa-th-list"></i><span> Manage Appointment</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                <?= Html::a('<i class="fa-th-list"></i><span> Disable</span>', ['appointment/disable', 'id' => $model->id], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                <ul class="nav nav-tabs nav-tabs-justified">
                    <li class="active">
                        <a href="#home-3" data-toggle="tab">
                            <span class="visible-xs"><i class="fa-home"></i></span>
                            <span class="hidden-xs">Appointment</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile-3" data-toggle="tab">
                            <span class="visible-xs"><i class="fa-user"></i></span>
                            <span class="hidden-xs">Estimated Proforma</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile-4" data-toggle="tab">
                            <span class="visible-xs"><i class="fa-user"></i></span>
                            <span class="hidden-xs">Port call Data</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile-5" data-toggle="tab">
                            <span class="visible-xs"><i class="fa-user"></i></span>
                            <span class="hidden-xs">Close Estimate</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="home-3">

                        <div class="panel-body"><div class="appointment-view">

                                <?php
//                                if($model->status == 1){
                                echo Html::a('<i class="fa fa-pencil"></i><span> Update</span>', ['appointment/update', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                                ?>
                                </p>
                                <?=
                                DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        //'id',
                                        [
                                            'attribute' => 'vessel_type',
                                            'value' => call_user_func(function($model) {

                                                            return VesselType::findOne($model->vessel_type)->vessel_type;
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'vessel',
                                            'label' => call_user_func(function($model) {
                                                            return VesselType::findOne($model->vessel_type)->vessel_type;
                                                    }, $model),
                                            'value' => call_user_func(function($model) {
                                                            if ($model->vessel_type == 1) {
                                                                    return 'T -' . Vessel::findOne($model->tug)->vessel_name . ' / B -' . Vessel::findOne($model->barge)->vessel_name;
                                                            } else {
                                                                    return Vessel::findOne($model->vessel)->vessel_name;
                                                            }
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'port_of_call',
                                            'value' => call_user_func(function($model) {

                                                            return Ports::findOne($model->port_of_call)->port_name;
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'terminal',
                                            'value' => call_user_func(function($model) {

                                                            return Terminal::findOne($model->terminal)->terminal;
                                                    }, $model),
                                        ],
                                        'birth_no',
                                        'appointment_no',
                                        'no_of_principal',
                                        [
                                            'attribute' => 'principal',
                                            'value' => call_user_func(function ($data) {
                                                            $principle = explode(',', $data->principal);
                                                            $result = '';
                                                            foreach ($principle as $prncple) {
                                                                    $result .= $data->getDebtorName($prncple) . ', ';
                                                            }
                                                            return rtrim($result, ",");
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'nominator',
                                            'value' => call_user_func(function($model) {

                                                            return Contacts::findOne($model->nominator)->name;
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'charterer',
                                            'value' => call_user_func(function($model) {

                                                            return Contacts::findOne($model->charterer)->name;
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'shipper',
                                            'value' => call_user_func(function($model) {

                                                            return Contacts::findOne($model->shipper)->name;
                                                    }, $model),
                                        ],
                                        [
                                            'attribute' => 'purpose',
                                            'value' => call_user_func(function($model) {

                                                            return Purpose::findOne($model->purpose)->purpose;
                                                    }, $model),
                                        ],
                                        'cargo',
                                        'cargo_details',
                                        'quantity',
                                        'last_port',
                                        'next_port',
                                        'eta',
                                        'stage',
                                        [
                                            'label' => 'Status',
                                            'format' => 'raw',
                                            'value' => $model->status == 1 ? 'Enabled' : 'disabled',
                                        ],
                                    ],
                                ])
                                ?>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="profile-3">

                        <div class="panel-body">
                            <div class="row" style="display: inline-block">
                                <div style="float: left;margin-left: 10px;">
                                    <?php
                                    if (empty($estimates)) {
                                            echo Html::a('<i class="fa fa-plus"></i><span> Add EstimatedProforma</span>', ['estimated-proforma/add', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                                    } else {
                                            echo Html::a('<i class="fa fa-pencil"></i><span> Update EstimatedProforma</span>', ['estimated-proforma/add', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                                    }
                                    ?>
                                </div>
                                <div style="float: left;margin-left: 8px;margin-right: 8px;">
                                    <?= Html::beginForm(['estimated-proforma/reports'], 'post', ['target' => '_blank']) ?>
                        <!--<form name="estimate" action="<?= Yii::$app->homeUrl ?>appointment/estimated-proforma/reports" method="post">-->
                                    <?php
                                    $arr = explode(',', $appointment->principal);
                                    if (count($arr) == 1) {
                                            ?>
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

                                            <?php
                                    }
                                    ?>
                                </div>
                                <div style="float: left;">
                                    <?= Html::submitButton('<i class="fa-print"></i><span>Generate Report</span>', ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']) ?>
<!--<input type="submit" name="b1" value="Submit">-->
                                    <?= Html::endForm() ?>
                                    <?php
//                    echo Html::a('<i class="fa-print"></i><span>Generate Report</span>', ['estimated-proforma/report', 'id' => $appointment->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']);
                                    ?> 
                                </div>
                            </div>


                            <!--                            <hr class="appoint_history" />-->

                            <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">#</th>
                                            <th data-priority="1">SERVICES</th>
                                            <th data-priority="3">SUPPLIER</th>
<!--                                            <th data-priority="3">CURRENCY</th>-->
                                            <th data-priority="1">RATE /QTY</th>
                                            <th data-priority="3">QTY</th>
                                            <th data-priority="6">ROE</th>
                                            <th data-priority="6">EPDA VALUE</th>
                                            <th data-priority="6">PRINCIPAL</th>
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
                                                    <th><span class="co-name"><?= $estimate->service->service ?></span></th>
                                                    <td><?= $estimate->supplier0->name ?></td>
        <!--                                                <td><?php // $estimate->currency0->currency_symbol                                      ?></td>-->
                                                    <td><?= $estimate->unit_rate; ?></td>
                                                    <td><?= $estimate->unit; ?></td>
                                                    <td><?= $estimate->roe; ?></td>
                                                    <td><?= $estimate->epda; ?></td>
                                                    <td><?= $estimate->principal0->principal_name; ?></td>
                                                    <td><?= $estimate->comments; ?></td>
                                                </tr>	

                                                <?php
                                        endforeach;
                                        ?>
                                        <!-- Repeat -->

                                    </tbody>

                                </table>

                            </div>
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



                                        $("#estimatedproforma-supplier").select2({
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


                                        $("#estimatedproforma-principal").select2({
                                            //placeholder: 'Select your country...',
                                            allowClear: true
                                        }).on('select2-open', function ()
                                        {
                                            // Adding Custom Scrollbar
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });



                                    });
                            </script>


                            <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                            <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                            <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                        </div>
                    </div>

                    <div class="tab-pane" id="profile-4">

                        <div class="panel-body">
                            <?php
                            if (empty($ports)) {
                                    echo Html::a('<i class="fa fa-plus"></i><span> Add PortCallData</span>', ['port-call-data/update', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                            } else {
                                    echo Html::a('<i class="fa fa-pencil"></i><span> Update PortCallData</span>', ['port-call-data/update', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                            }
                            ?>

                            <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                <div class="port-call-data-form form-inline" style="padding-top: 10px;">
                                    <p style="margin-left: 35px;margin-bottom: 10px;font-size: 23px;color: black;"><b>PortCallData</b></p>
                                    <?php
                                    if (!empty($imigration)) {
                                            echo '<p style="margin-left: 35px;margin-bottom: 10px;font-size: 18px;color:#09a2dc;"><b>Imigration Clearence Details</b></p>';
                                            echo '<hr class="appoint_history" />';
                                            echo $this->render('_imigration', [
                                                'model' => $imigration,
                                                'form' => $form,
                                            ]);
                                    }
                                    ?>

                                    <hr class="appoint_history" />
                                    <p style="margin-left: 35px;margin-bottom: 10px;font-size: 18px;color:#09a2dc;"><b>Port Call Details</b></p>
                                    <hr class="appoint_history" />
                                    <div class="form-group field-portcalldata-eta">
                                        <label class="control-label" for="portcalldata-eta">ETA  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($ports->eta) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">ETS  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($ports->ets) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eosp">
                                        <label class="control-label" for="portcalldata-eosp">EOSP  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($ports->eosp) ?>
                                        <?= $$newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eosp">
                                        <label class="control-label" for="portcalldata-eosp">ARRIVED ANCHORAGE  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($ports->arrived_anchorage) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eosp">
                                        <label class="control-label" for="portcalldata-eosp">NOR TENDERED  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($ports->nor_tendered) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <?php
                                    if ($appointment->port_of_call == 2 || $appointment->port_of_call == 3) {
                                            if ($appointment->purpose == 2 && $appointment->port_of_call == 2) {
                                                    //$var = "_form_rmc_tanker";
                                                    echo $this->render('_rmc_tanker', [
                                                        'model' => $ports,
                                                        'form' => $form,
                                                    ]);
                                            } else {
                                                    //$var = "_form_stevin_rocks";
                                                    echo $this->render('_stevin_rocks', [
                                                        'model' => $ports,
                                                        'form' => $form,
                                                    ]);
                                            }
                                    } else {
                                            //$var = "_form_common";
                                            echo $this->render('_common', [
                                                'model' => $ports,
                                                'form' => $form,
                                            ]);
                                    }
                                    ?>

                                    <div class="form-group field-portcalldata-eosp">
                                        <label class="control-label" for="portcalldata-eosp">COMMENTS  :</label>
                                        <?= $ports->comments; ?>

                                        <div class="help-block"></div>
                                    </div>


                                </div>

                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                            </div>
                        </div>

                        <div class="panel-body">
                            <?php
                            if (empty($drafts)) {
                                    echo Html::a('<i class="fa fa-plus"></i><span> Add PortCallDataDraft</span>', ['port-call-data/update', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                            } else {
                                    echo Html::a('<i class="fa fa-pencil"></i><span> Update PortCallDataDraft</span>', ['port-call-data/update', 'id' => $drafts->appointment_id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                            }
                            ?>
                            <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                <div class="port-call-data-form form-inline" style="padding-top: 10px;">
                                    <p style="margin-left: 35px;margin-bottom: 10px;font-size: 23px;color: black;"><b>PortCallDataDraft</b></p>
                                    <hr class="appoint_history" />
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">DATA ID  :</label>
                                        <?= $drafts->data_id; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eta">
                                        <label class="control-label" for="portcalldata-eta">INITIAL SURVEY COMMENCED  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($drafts->intial_survey_commenced) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eta">
                                        <label class="control-label" for="portcalldata-eta">INITIAL SURVEY COMPLETED  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($drafts->intial_survey_commenced) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eta">
                                        <label class="control-label" for="portcalldata-eta">FINAL SURVEY COMMENCED  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($drafts->finial_survey_commenced) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-eta">
                                        <label class="control-label" for="portcalldata-eta">FINAL SURVEY COMPLETED  :</label>
                                        <?= Yii::$app->SetValues->ChangeFormate($drafts->finial_survey_completed) ?>
                                        <?= $newDate; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <!--                                    <div class="form-group field-portcalldata-ets">
                                                                            <label class="control-label" for="portcalldata-ets">FORWARD ARRIVAL UNIT  :</label>
                                    <?= $drafts->fwd_arrival_unit; ?>
                                    
                                                                            <div class="help-block"></div>
                                                                        </div>-->
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">FWD ARRIVAL QUANTITY  :</label>
                                        <?= $drafts->fwd_arrival_quantity; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <!--                                    <div class="form-group field-portcalldata-ets">
                                                                            <label class="control-label" for="portcalldata-ets">AFTER ARRIVAL UNIT  :</label>
                                    <?= $drafts->aft_arrival_unit; ?>
                                    
                                                                            <div class="help-block"></div>
                                                                        </div>-->
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">AFT ARRIVAL QUANTITY  :</label>
                                        <?= $drafts->aft_arrival_quantity; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <!--                                    <div class="form-group field-portcalldata-ets">
                                                                            <label class="control-label" for="portcalldata-ets">mean_arrival_unit  :</label>
                                    <?= $drafts->mean_arrival_unit; ?>
                                    
                                                                            <div class="help-block"></div>
                                                                        </div>-->
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">MEAN ARRIVAL QUANTITY  :</label>
                                        <?= $drafts->mean_arrival_quantity; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <!--                                    <div class="form-group field-portcalldata-ets">
                                                                            <label class="control-label" for="portcalldata-ets">fwd_sailing_unit  :</label>
                                    <?= $drafts->fwd_sailing_unit; ?>
                                    
                                                                            <div class="help-block"></div>
                                                                        </div>-->
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">FWD SAILING QUANTITY  :</label>
                                        <?= $drafts->fwd_sailing_quantity; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <!--                                    <div class="form-group field-portcalldata-ets">
                                                                            <label class="control-label" for="portcalldata-ets">aft_sailing_unit  :</label>
                                    <?= $drafts->aft_sailing_unit; ?>
                                    
                                                                            <div class="help-block"></div>
                                                                        </div>-->
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">AFT SAILING QUANTITY  :</label>
                                        <?= $drafts->aft_sailing_quantity; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <!--                                    <div class="form-group field-portcalldata-ets">
                                                                            <label class="control-label" for="portcalldata-ets">mean_sailing_unit  :</label>
                                    <?= $drafts->mean_sailing_unit; ?>
                                    
                                                                            <div class="help-block"></div>
                                                                        </div>-->
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">MEAN SAILING QUANTITY  :</label>
                                        <?= $drafts->mean_sailing_quantity; ?>

                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">COMMENTS  :</label>
                                        <?= $drafts->comments; ?>

                                        <div class="help-block"></div>
                                    </div>

                                </div>

                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                            </div>
                        </div>

                        <div class="panel-body">
                            <?php
                            if (empty($rob)) {
                                    echo Html::a('<i class="fa fa-plus"></i><span> Add PortCallDataRob</span>', ['port-call-data/update', 'id' => $model->appointment_id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                            } else {
                                    echo Html::a('<i class="fa fa-pencil"></i><span> Update PortCallDataRob</span>', ['port-call-data/update', 'id' => $rob->appointment_id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                            }
                            ?>
                            <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                <div class="port-call-data-form form-inline" style="padding-top: 10px;">
                                    <p style="margin-left: 35px;margin-bottom: 10px;font-size: 23px;color: black;"><b>PortCallDataRob</b></p>
                                    <hr class="appoint_history" />
                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">FO ARRIVAL  :</label>
                                        <?= $rob->fo_arrival_quantity; ?> <?php
                                        if ($rob->fo_arrival_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->fo_arrival_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">DO ARRIVAL  :</label>
                                        <?= $rob->do_arrival_quantity; ?> <?php
                                        if ($rob->do_arrival_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->do_arrival_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">GO ARRIVAL :</label>
                                        <?= $rob->go_arrival_quantity; ?> <?php
                                        if ($rob->go_arrival_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->go_arrival_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">LO ARRIVAL :</label>
                                        <?= $rob->lo_arrival_quantity; ?> <?php
                                        if ($rob->lo_arrival_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->lo_arrival_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">FRESH WATER ARRIVAL :</label>
                                        <?= $rob->fresh_water_arrival_quantity; ?> <?php
                                        if ($rob->fresh_water_arrival_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->fresh_water_arrival_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">FO SAILING :</label>
                                        <?= $rob->do_sailing_quantity; ?> <?php
                                        if ($rob->fo_sailing_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->fo_sailing_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">DO SAILING :</label>
                                        <?= $rob->do_sailing_quantity; ?> <?php
                                        if ($rob->do_sailing_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->do_sailing_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">GO SAILING :</label>
                                        <?= $rob->go_sailing_quantity; ?> <?php
                                        if ($rob->go_sailing_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->go_sailing_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">LO SAILING :</label>
                                        <?= $rob->lo_sailing_quantity; ?> <?php
                                        if ($rob->lo_sailing_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->lo_sailing_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets">
                                        <label class="control-label" for="portcalldata-ets">FRESH WATER SAILING  :</label>
                                        <?= $rob->fresh_water_sailing_quantity; ?> <?php
                                        if ($rob->fresh_water_sailing_unit == 1) {
                                                echo 'Metric Ton';
                                        } elseif ($rob->fresh_water_sailing_unit == 2) {
                                                echo 'Litter';
                                        }
                                        ?>

                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group field-portcalldata-ets" style="padding-right: 50px !important;">
                                        <label class="control-label" for="portcalldata-ets">COMMENTS  :</label>
                                        <?= $rob->comments; ?>

                                        <div class="help-block"></div>
                                    </div>

                                </div>

                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                                <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                                <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="profile-5">

                        <div class="panel-body">
                            <div class="row">
                                <div style="float: left;margin-left: 10px;">
                                    <?php
                                    if (empty($closeestimates)) {
                                            echo Html::a('<i class="fa fa-plus"></i><span> Add CloseEstimate</span>', ['close-estimate/add', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                                    } else {
                                            echo Html::a('<i class="fa fa-pencil"></i><span> Update CloseEstimate</span>', ['close-estimate/add', 'id' => $model->id], ['class' => 'btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right']);
                                    }
                                    ?>
                                </div>
                                <div style="float: left;margin-left: 10px;">
                                    <?php
                                    echo Html::a('<i class="fa-print"></i><span>Generate Report</span>', ['close-estimate/report', 'id' => $appointment->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']);
                                    ?>
                                </div>
                            </div>
                            <div class="table-responsive" data-pattern="priority-columns" data-focus-btn-icon="fa-asterisk" data-sticky-table-header="true" data-add-display-all-btn="true" data-add-focus-btn="true">

                                <table cellspacing="0" class="table table-small-font table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">#</th>
                                            <th data-priority="1">SERVICES</th>
                                            <th data-priority="3">SUPPLIER</th>
                                            <th data-priority="1">RATE /QTY</th>
                                            <th data-priority="3">QTY</th>
                                            <th data-priority="6">ROE</th>
                                            <th data-priority="6">EPDA VALUE</th>
                                            <th data-priority="6">FDA VALUE</th>
                                            <th data-priority="6">PAYMENT TYPE</th>
                                            <th data-priority="6">TOTAL</th>
                                            <th data-priority="6">PRINCIPAL</th>
                                            <th data-priority="6">COMMENTS</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($closeestimates as $closeestimate):
                                                $i++;
                                                ?>
                                                <tr>
                                                    <td><?= $i; ?></td>
                                                    <th><span class="co-name"><?= $closeestimate->service->service ?></span></th>
                                                    <td><?= $closeestimate->supplier0->name ?></td>
                                                    <td><?= $closeestimate->unit_rate; ?></td>
                                                    <td><?= $closeestimate->unit; ?></td>
                                                    <td><?= $closeestimate->roe; ?></td>
                                                    <td><?= $closeestimate->epda; ?></td>
                                                    <td><?= $closeestimate->fda; ?></td>
                                                    <td><?= $closeestimate->payment_type; ?></td>
                                                    <td><?= $closeestimate->total; ?></td>
                                                    <td><?= $closeestimate->principal0->principal_name; ?></td>
                                                    <td><?= $closeestimate->comments; ?></td>
                                                </tr>	

                                                <?php
                                        endforeach;
                                        ?>
                                        <!-- Repeat -->

                                    </tbody>

                                </table>

                            </div>
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



                                        $("#estimatedproforma-supplier").select2({
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


                                        $("#estimatedproforma-principal").select2({
                                            //placeholder: 'Select your country...',
                                            allowClear: true
                                        }).on('select2-open', function ()
                                        {
                                            // Adding Custom Scrollbar
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });



                                    });
                            </script>


                            <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
                            <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
                            <script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>


                        </div>
                    </div>
                    <?php
//                                 }
                    ?>
                </div>
            </div>
        </div>
    </div>


