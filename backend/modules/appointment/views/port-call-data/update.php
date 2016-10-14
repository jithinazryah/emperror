<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Services;
use common\models\Currency;
use common\models\Contacts;
use common\models\Debtor;
use common\models\Appointment;
use common\models\PortBreakTimings;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use common\components\AppointmentWidget;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallData */
$stat = $_GET['stat'];
$this->title = 'Update Port Call Data: ';
$this->params['breadcrumbs'][] = ['label' => 'Port Call Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) . ' # <b style="color: #008cbd;">' . $model->appointment->appointment_no . '</b>' ?></h3>

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
                <?= AppointmentWidget::widget(['id' => $model_appointment->id]) ?>

                <hr class="appoint_history" />
                <div style="float:left;">
                    <?php
                    echo Html::a('<i class="fa-print"></i><span>Generate SOF Report</span>', ['port-call-data/reports', 'id' => $model_appointment->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone']);
                    ?>
                </div>
                <div style="float: left;">
                    <ul class="nav nav-tabs nav-tabs-justified">
                        <li>
                            <?php
                            echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Appointment</span>', ['appointment/update', 'id' => $model_appointment->id]);
                            ?>

                        </li>
                        <li>
                            <?php
                            echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Estimated Proforma</span>', ['estimated-proforma/add', 'id' => $model_appointment->id]);
                            ?>

                        </li>
                        <li class="active">
                            <?php
                            echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Port call Data</span>', ['port-call-data/update', 'id' => $model_appointment->id]);
                            ?>

                        </li>
                        <li>
                            <?php
                            echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Close Estimate</span>', ['close-estimate/add', 'id' => $model_appointment->id]);
                            ?>

                        </li>
                    </ul>
                    <?php //Html::a('<i class="fa-th-list"></i><span> Manage Port Call Data</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone'])  ?>
                    <ul class="nav nav-tabs nav-tabs-justified colo" style="background-color:#CCCCCC;padding-top: 5px;">
                        <li class="<?= $stat == 1 || $stat == NULL ? 'active' : '' ?>">
                            <a  href="#port-data" data-toggle="tab">
                                <span class="visible-xs"><i class="fa-home"></i></span>
                                <span class="hidden-xs">Port Call Data</span>
                            </a>
                        </li>
                        <li class="<?= $stat == 2 ? 'active' : '' ?>">
                            <a href="#port-draft" data-toggle="tab">
                                <span class="visible-xs"><i class="fa-user"></i></span>
                                <span class="hidden-xs">Port Call Data Draft-Rob</span>
                            </a>
                        </li>
                        <li class="<?= $stat == 3 ? 'active' : '' ?>">
                            <a href="#port-rob" data-toggle="tab">
                                <span class="visible-xs"><i class="fa-user"></i></span>
                                <span class="hidden-xs">Port Break Timings</span>
                            </a>
                        </li>
                    </ul>    
                    <div class="tab-content">
                        <div class="tab-pane <?= $stat == 1 || $stat == NULL ? 'active' : '' ?>" id="port-data">
                            <div class="panel-body">
                                <div class="port-call-data-create">
                                    <?=
                                    $this->render('_form', [
                                        'model' => $model,
                                        'model_add' => $model_add,
                                        'model_imigration' => $model_imigration,
                                        'model_appointment' => $model_appointment,
                                        'model_additional' => $model_additional,
                                    ])
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane <?= $stat == 2 ? 'active' : '' ?>" id="port-draft">
                            <div class="panel-body">
                                <div class="port-call-data-draft-create">
                                    <?=
                                    $this->render('_form_draft_rob', [
                                        'model_draft' => $model_draft,
                                        'model_rob' => $model_rob,
                                        'model' => $model,
                                    ])
                                    ?>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane <?= $stat == 3 ? 'active' : '' ?>" id="port-rob">
                            <div class="panel-body">
                                <div class="port-call-data-port-break-create">
                                    <?=
                                    $this->render('_form_port_break', [
                                        'model_appointment' => $model_appointment,
                                        'model_port_break' => $model_port_break,
                                        'model_port_cargo_details' => $model_port_cargo_details,
                                        'model_port_stoppages' => $model_port_stoppages,
                                    ])
                                    ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div style="float:right;padding-top: 5px;">
                    <?php
                    echo Html::a('<span> Portcall Data Completed & Proceed to Close Estimate</span>', ['port-call-data/portcall-conmplete', 'id' => $model_appointment->id], ['class' => 'btn btn-secondary']);
                    ?>
                </div>          
            </div>
        </div>
        <style>
            .colo.nav.nav-tabs>li.active>a {
                background-color: #b9c7a7;
            }
            .colo.nav.nav-tabs>li>a:hover {
                border: none;
                background-color: #c3d2b0;
            }
            .form-control{
                border: 1px solid #8a8a8a;
            }

        </style>
    </div>
