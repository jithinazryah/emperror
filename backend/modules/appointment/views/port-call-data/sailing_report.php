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

        <tbody>
            <tr>
                <td>
                    <div class="general-details">
                        <h6>General Details:</h6>
                        <table>
                            <tr>
                                <td style="width: 50%;">Vessel Name</td>
                                <td style="width: 50%;">:<?php
                                    if ($appointment->vessel_type == 1) {
                                            echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                    } else {
                                            echo Vessel::findOne($appointment->vessel)->vessel_name;
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Load Port</td>
                                <td style="width: 50%;">:<?= $appointment->cargo ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Last Port</td>
                                <td style="width: 50%;">:<?= $appointment->last_port ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Next Port</td>
                                <td style="width: 50%;">:<?= $appointment->next_port ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Cargo Quantity</td>
                                <td style="width: 50%;">:<?php
                                    if (empty($ports_cargo->loaded_quantity)) {
                                            echo $appointment->quantity;
                                    } else {
                                            echo $ports_cargo->loaded_quantity;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">Cargo type</td>
                                <td style="width: 30%;">:<?= $ports_cargo->cargo_type ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">Operation</td>
                                <td style="width: 30%;">:<?= Purpose::findOne($appointment->purpose)->purpose; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">NOR Tendered</td>
                                <td style="width: 30%;">:<?= Yii::$app->SetValues->DateFormate($ports->nor_tendered); ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="timings">
                        <h6>Timings:</h6>
                        <table>
                            <tr>
                                <td style="width: 50%;">ETA</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->eta); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">ETS</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->ets); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">NOR Tendered</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->nor_tendered); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">POB Inbound</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->pob_inbound); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">First Line Ashore</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->first_line_ashore); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">All Fast</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->all_fast); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Gangway Down</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->gangway_down); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Agent On Board</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->agent_on_board); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Immigration Commenced</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->immigration_commenced); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Immigration Completed</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->immigartion_completed); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Last Line Away</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->lastline_away); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Pilot Away</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->pilot_away); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Customs Clearance On Arrival</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->customs_clearance_onarrival); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Customs Clearance On Departure</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->customs_clearance_ondeparture); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Hoses Connected</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->hoses_connected); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Pre Discharge Safety</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->pre_discharge_safety); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Hoses Disconnected</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->hoses_disconnected); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">SBE</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->sbe); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Surveyor On Board</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->surveyor_on_board); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Cargo Operation Commenced</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->cargo_commenced); ?></td>

                            </tr>
                            <tr>
                                <td style="width: 50%;">Cargo Operation Completed</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->cargo_completed); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Pilot on board</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->pob_outbound); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Cast off from berth</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->cast_off); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">COSP</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->cosp); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">ETA Next Port</td>
                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports->eta_next_port); ?>
                                </td>
                            </tr>
                            <?php
                            if (!empty($ports_additional)) {
                                    foreach ($ports_additional as $ports_add) {
                                            ?>
                                            <tr>
                                                <td style="width: 50%;"><?= $ports_add->label ?></td>
                                                <td style="width: 50%;">:<?= Yii::$app->SetValues->DateFormate($ports_add->value); ?>
                                                </td>
                                            </tr>   
                                    <?php
                                    }
                            }
                            ?>
                        </table>
                    </div>

                    <div class="rob-sailing">
                        <h6>ROB-SAILING:</h6>
                        <table>
                            <tr>
                                <td style="width: 50%;">FO</td>
                                <td style="width: 50%;">:<?php
                                    if ($ports_rob->fo_sailing_quantity != '' && $ports_rob->fo_sailing_quantity != NULL) {
                                            echo $ports_rob->fo_sailing_quantity
                                            ?> <?=
                                            $ports_rob->fo_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">DO</td>
                                <td style="width: 50%;">:<?php
                                    if ($ports_rob->do_sailing_quantity != '') {
                                            echo $ports_rob->do_sailing_quantity
                                            ?> <?=
                                            $ports_rob->do_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">Fresh Water</td>
                                <td style="width: 50%;">:<?php
                                    if ($ports_rob->fresh_water_sailing_quantity != '') {
                                            echo $ports_rob->fresh_water_sailing_quantity
                                            ?> <?=
                                            $ports_rob->fresh_water_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="draft-departure">
                        <h6>DRAFT DEPARTURE:</h6>
                        <table>
                            <tr>
                                <td style="width: 50%;">FWD</td>
                                <td style="width: 50%;">:<?php
                                    if ($ports_draft->fwd_sailing_quantity != '') {
                                            echo $ports_draft->fwd_sailing_quantity . ' m';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">AFT</td>
                                <td style="width: 50%;">:<?php
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
