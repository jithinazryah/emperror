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
            border: 1px solid black;
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
                            <?php
                            if (!empty($ports_imigration)) {
                                    foreach ($ports_imigration as $key => $value) {
                                            $check = ['id', 'appointment_id', 'status', 'CB', 'UB', 'DOC', 'DOU'];
                                            if (!in_array($key, $check)) {
                                                    if ($value != '') {
                                                            ?>
                                                            <tr>
                                                                <td style = "width: 50%;"><?= $key ?></td>
                                                                <td style = "width: 50%;">:<?= Yii::$app->SetValues->DateFormate($value); ?></td>
                                                            </tr> 
                                                            <?php
                                                    }
                                            }
                                    }
                            }
                            ?>
                            <?php
                            foreach ($ports as $key => $value) {
                                    $check = ['id', 'appointment_id', 'status', 'CB', 'UB', 'DOC', 'DOU', 'cleared_channel', 'fasop', 'sampling', 'tank_inspection_completed', 'additional_info', 'comments'];
                                    if (!in_array($key, $check)) {
                                            if ($value != '') {
                                                    ?>
                                                    <tr>
                                                        <td style = "width: 50%;"><?= $key ?></td>
                                                        <td style = "width: 50%;">:<?= Yii::$app->SetValues->DateFormate($value); ?></td>
                                                    </tr> 
                                                    <?php
                                            }
                                    }
                            }
                            ?>

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
                </td>
            </tr>
            <tr>
                <td>
                    <div class="rob-sailing">
                        <h6>ROB-SAILING:</h6>
                        <table>
                            <tr>
                                <td style="width: 33%;">FO</td>
                                <td style="width: 33%;">DO</td>
                                <td style="width: 34%;">FW</td>
                            </tr>
                            <tr>
                                <td style="width: 33%;"><?php
                                    if ($ports_rob->fo_sailing_quantity != '' && $ports_rob->fo_sailing_quantity != NULL) {
                                            echo $ports_rob->fo_sailing_quantity
                                            ?> <?=
                                            $ports_rob->fo_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></td>
                                <td style="width: 33%;"><?php
                                    if ($ports_rob->do_sailing_quantity != '') {
                                            echo $ports_rob->do_sailing_quantity
                                            ?> <?=
                                            $ports_rob->do_sailing_unit == 1 ? 'MT' : 'L';
                                    }
                                    ?></td>
                                <td style="width: 34%;"><?php
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
                                <td style="width: 50%;">AFT</td>
                            </tr>
                            <tr>
                                <td style="width: 50%;"><?php
                                    if ($ports_draft->fwd_sailing_quantity != '') {
                                            echo $ports_draft->fwd_sailing_quantity . ' m';
                                    }
                                    ?></td>
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
