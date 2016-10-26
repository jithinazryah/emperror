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
                    <div class="general-details">
                        <h6>General Details:</h6>
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
                                <td style="width: 20%;">Load Port</td>
                                <td style="width: 30%;"><?= $appointment->cargo ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">Last Port</td>
                                <td style="width: 30%;"><?= $appointment->last_port ?></td>
                                <td style="width: 20%;">Next Port</td>
                                <td style="width: 30%;"><?= $appointment->next_port ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">Cargo Quantity</td>
                                <td style="width: 30%;"><?php
                                    if (empty($ports_cargo->loaded_quantity)) {
                                            echo $appointment->quantity;
                                    } else {
                                            echo $ports_cargo->loaded_quantity;
                                    }
                                    ?>
                                </td>
                                <td style="width: 20%;">Cargo type</td>
                                <td style="width: 30%;"><?= $ports_cargo->cargo_type ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">Operation</td>
                                <td style="width: 30%;"><?= Purpose::findOne($appointment->purpose)->purpose; ?></td>
                                <td style="width: 20%;">NOR Tendered</td>
                                <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports->nor_tendered); ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="timings">
                        <h6>Timings:</h6>
                        <table class="table tbl">
                            <tr>
                                <td style="width: 30%;">Cargo Operation Commenced</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->cargo_commenced); ?></td>
                                <td style="width: 30%;">Cargo Operation Completed</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->cargo_completed); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">Pilot on board</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->pob_outbound); ?></td>
                                <td style="width: 30%;">Cast off from berth</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->cast_off); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 30%;">COSP</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->cosp); ?></td>
                                <td style="width: 30%;">ETA Next Port</td>
                                <td style="width: 20%;"><?= Yii::$app->SetValues->DateFormate($ports->eta_next_port); ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="rob-sailing">
                        <h6>ROB-SAILING:</h6>
                        <table class="table tbl">
                            <tr>
                                <th style="width: 33%;">FO</th>
                                <th style="width: 33%;">DO</th>
                                <th style="width: 34%;">Fresh Water</th>
                            </tr>
                            <tr>
                                <th style="width: 33%;"><?php
                                    if ($ports_rob->fo_sailing_quantity != '' && $ports_rob->fo_sailing_quantity != NULL) {
                                            echo $ports_rob->fo_sailing_quantity
                                            ?> <?=
                                            $ports_rob->fo_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></th>
                                <th style="width: 33%;"><?php
                                    if ($ports_rob->do_sailing_quantity != '') {
                                            echo $ports_rob->do_sailing_quantity
                                            ?> <?=
                                            $ports_rob->do_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></th>
                                <th style="width: 34%;"><?php
                                    if ($ports_rob->fresh_water_sailing_quantity != '') {
                                            echo $ports_rob->fresh_water_sailing_quantity
                                            ?> <?=
                                            $ports_rob->fresh_water_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></th>
                            </tr>
                        </table>
                    </div>

                    <div class="draft-departure">
                        <h6>DRAFT DEPARTURE:</h6>
                        <table class="table tbl">
                            <tr>
                                <td style="width: 50%;">FWD</td>
                                <td style="width: 50%;"><?php
                                    if ($ports_draft->fwd_sailing_quantity != '') {
                                            echo $ports_draft->fwd_sailing_quantity . ' m';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">AFT</td>
                                <td style="width: 50%;"><?php
                                    if ($ports_draft->aft_sailing_quantity != '') {
                                            echo $ports_draft->aft_sailing_quantity . ' m';
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
