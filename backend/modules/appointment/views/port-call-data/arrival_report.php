<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubServices;
use common\models\Appointment;
use common\models\EstimatedProforma;
use common\models\Debtor;
use common\models\PortCallData;
use common\models\Vessel;
use common\models\Purpose;
use common\models\Ports;
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<div id="print">
    <!--<html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title></title>-->
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl ?>/css/pdf.css">
    <style type="text/css">

        @media print {
            thead {display: table-header-group;}
            .main-tabl{width: 100%}
        }
        @media screen{
            .main-tabl{
                width: 60%;
            }
        }
        .table td {
            // border: 1px solid black;
            font-size: 12px;
            text-align: left;
            padding: 7px;
            /*font-weight: bold;*/
        }
        .cargodetails{
            page-break-inside: avoid;
        }
        .print{
            margin-top: 18px;
            margin-left: 365px;
        }
    </style>
    <!--    </head>
        <body >-->
    <table class="main-tabl" border="0"> 
        <thead> 
            <tr> 
                <th style="width:100%">
        <div class="header">
            <div class="main-left">
                <img src="<?= Yii::$app->homeUrl ?>/images/report-logo.jpg" style="width: 100px;height: 100px;"/>

            </div>
            <div class="main-right">
                <h2>Statement Of Facts</h2>
                <h2 style="font-style: italic;font-size: 18px;"><?= $appointment->appointment_no ?></h2>
            </div>
            <br/>
        </div>
        </th> 
        </tr> 

        </thead> 
        <tbody>
            <tr>
                <td>
                    <div class="vessel-details">
                        <table class="table tbl">
                            <tr>
                                <td style="width: 20%;">Vessel Name</td>
                                <td style="width: 30%;"><?php
                                    if ($appointment->vessel_type == 1) {
                                            echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                    } else {
                                            echo Vessel::findOne($appointment->vessel)->vessel_name;
                                    }
                                    ?></td>
                                <td style="width: 20%;">Last Port</td>
                                <td style="width: 30%;"><?= $appointment->last_port ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">Next Port</td>
                                <td style="width: 30%;"><?= $appointment->next_port ?></td>
                                <td style="width: 20%;">Port / Berth no</td>
                                <td style="width: 30%;"><?= Ports::findOne($appointment->port_of_call)->port_name; ?>/<?= $appointment->birth_no ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">NOR Tendered</td>
                                <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports->nor_tendered); ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="arrival-timings">
                        <h6>Arrival Timings:</h6>
                        <table class="table tbl">
                            <tr>
                                <td style="width: 30%;">EOSP</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->eosp); ?></td>
                                <td style="width: 30%;">Arrived at Anchorage</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->arrived_anchorage); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">Dropped Anchor</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->dropped_anchor); ?></td>
                                <td style="width: 30%;">Heave up anchor</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($port_imigration->heave_up_anchor); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">Arrived P/s</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($port_imigration->arrived_ps); ?></td>
                                <td style="width: 30%;">POB-(inbound)</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->pob_inbound); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">First Line Ashore</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->first_line_ashore); ?></td>
                                <td style="width: 30%;">All Fast</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->all_fast); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">Draft Survey (commenced)</td>
                                <td style="width: 20%;"></td>
                                <td style="width: 30%;">Draft Survey (completed</td>
                                <td style="width: 20%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">Expected Loading Commencement</td>
                                <td style="width: 20%;"></td>
                            </tr>
                        </table>
                    </div>

                    <div class="rob-arrival">
                        <h6>Arrival - ROB (berth):</h6>
                        <table class="table tbl">
                            <tr>
                                <th style="width: 33%;">FO</th>
                                <th style="width: 33%;">DO</th>
                                <th style="width: 34%;">Fresh Water</th>
                            </tr>
                            <tr>
                                <th style="width: 33%;"><?php
                                    if ($ports_rob->fo_arrival_quantity != '') {
                                            echo $ports_rob->fo_arrival_quantity
                                            ?><?=
                                            $ports_rob->fo_arrival_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></th>
                                <th style="width: 33%;"><?php
                                    if ($ports_rob->do_arrival_quantity != '') {
                                            echo $ports_rob->do_arrival_quantity
                                            ?> <?=
                                            $ports_rob->do_arrival_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></th>
                                <th style="width: 34%;"><?php
                                    if ($ports_rob->fresh_water_arrival_quantity != '') {
                                            echo $ports_rob->fresh_water_arrival_quantity
                                            ?> <?=
                                            $ports_rob->fresh_water_arrival_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></th>
                            </tr>
                        </table>
                    </div>

                    <div class="draft-departure">
                        <h6>Draft - Arrival:</h6>
                        <table class="table tbl">
                            <tr>
                                <td style="width: 50%;">FWD</td>
                                <td style="width: 50%;"><?php
                                    if ($ports_draft->fwd_arrival_quantity != '') {
                                            echo $ports_draft->fwd_arrival_quantity . ' m';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">AFT</td>
                                <td style="width: 50%;"><?php
                                    if ($ports_draft->aft_arrival_quantity != '') {
                                            echo $ports_draft->aft_arrival_quantity . ' m';
                                    }
                                    ?></td>
                            </tr>
                        </table>
                    </div>

                </td>
            </tr>
        </tbody>
    </table>
</div>
<!--</body>-->
<script>
        function printContent(el) {
            var restorepage = document.body.innerHTML;
            var printcontent = document.getElementById(el).innerHTML;
            document.body.innerHTML = printcontent;
            window.print();
            document.body.innerHTML = restorepage;
        }
</script>
<div class="print">
    <button onclick="printContent('print')" style="font-weight: bold !important;">Print</button>
    <button onclick="window.close();" style="font-weight: bold !important;">Close</button>
</div>


<!--</html>-->
