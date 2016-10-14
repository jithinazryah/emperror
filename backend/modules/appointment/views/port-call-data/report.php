<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubServices;
use common\models\Appointment;
use common\models\EstimatedProforma;
use common\models\Debtor;
use common\models\PortCallData;
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title></title>
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
        </style>
    </head>
    <body>
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
                    <table class="">

                    </table>
                </div>
                <br/>
            </div>
        </th> 
    </tr> 

</thead> 
<tbody>
    <tr>
        <td>


            <div class="content">
                <table class="table tbl">
                    <tr>
                        <td style="width: 20%;">Vessel Name</td>
                        <td style="width: 30%;"><?= $appointment->vessel0->vessel_name ?></td>
                        <td style="width: 20%;">Cargo Quantity</td>
                        <td style="width: 30%;"><?= $appointment->quantity ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Load Port</td>
                        <td style="width: 30%;"><?= $appointment->portOfCall->port_name ?></td>
                        <td style="width: 20%;">Cargo Type</td>
                        <td style="width: 30%;"><?= $appointment->cargo ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Last Port</td>
                        <td style="width: 30%;"><?= $appointment->last_port ?></td>
                        <td style="width: 20%;">Operation</td>
                        <td style="width: 30%;"><?= $appointment->purpose0->purpose ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Next Port</td>
                        <td style="width: 30%;"><?= $appointment->next_port ?></td>
                        <td style="width: 20%;">NOR Tendered</td>
                        <td style="width: 30%;"><?= $ports->nor_tendered ?></td>
                    </tr>

                </table>
            </div>


        </td>
    </tr>
    <tr>
        <td>


            <div class="events">
                <?php
                $port = $this->context->portcallReport($ports, 'ports');
//                echo "<pre>";
//                var_dump($port['ports']['mins']);
//                exit;
                if (!empty($port['ports']['no_mins']))
                        uasort($port['ports']['no_mins'], 'cmp');

                if (!empty($port['ports']['mins']))
                        uasort($port['ports']['mins'], 'cmp');

                function cmp($port, $b) {
                        return strtotime($port) < strtotime($b) ? -1 : 1;
                }

                if (!empty($port)) {
                        ?>
                        <h6>Events</h6>
                        <table class="table">
                            <?php
                            $flag = 0;
                            if (!empty($port['ports']['mins'])) {
                                    foreach ($port['ports']['mins'] as $key => $value) {
                                            $flag++;
                                            if ($flag == 1) {
                                                    echo "<tr>";
                                            }
                                            ?>
                                            <td style="width: 20%;"><?= $key; ?></td>
                                            <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($value); ?></td>
                                            <?php
                                            if ($flag == 2) {
                                                    echo "</tr>";
                                                    $flag = 0;
                                            }
                                    }
                            }

                            if (!empty($port['ports']['no_mins'])) {
                                    echo '<tr><td style="border:none;"></td></tr>';
                                    foreach ($port['ports']['no_mins'] as $key => $value) {
                                            $flag++;
                                            if ($flag == 1) {
                                                    echo '<tr>';
                                            }
                                            ?>
                                            <td style="width: 20%;"><?= $key; ?></td>
                                            <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($value); ?></td>
                                            <?php
                                            if ($flag == 2) {
                                                    echo "</tr>";
                                                    $flag = 0;
                                            }
                                    }
                            }
                            $flag++;
                            if ($flag == 1) {
                                    echo "<tr>";
                            }
                            ?>
                            <td style="width: 20%;">ETA Next Port</td>
                            <td style="width: 30%;"><?= $ports->eta_next_port ?></td>
                            <?php
                            if ($flag == 2) {
                                    echo "</tr>";
                            }
                            ?>

                        </table>
                        <?php
                }
                ?>




            </div>



        </td>
    </tr>
    <tr>
        <td>



            <div class="survey_cargo">
                <h6>Survey/Cargo Timings</h6>
                <table class="table">
                    <tr>
                        <td style="width: 20%;"><?php if ($appointment->vessel_type == 3) { ?>Ullaging / Sampling Commenced<?php } else { ?>Initial Draft Survey (Commenced)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->intial_survey_commenced); ?></td>
                        <td style="width: 20%;"><?php if ($appointment->vessel_type == 3) { ?>Tank Inspection Commenced<?php } else { ?>Final Draft Survey (Commenced)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->finial_survey_commenced); ?></td>
                    </tr>
                    <tr >
                        <td style="width: 20%;"><?php if ($appointment->vessel_type == 3) { ?>Ullaging / Sampling Completed<?php } else { ?>Initial Draft Survey (Completed)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->intial_survey_completed); ?></td>
                        <td style="width: 20%;"><?php if ($appointment->vessel_type == 3) { ?>Tank Inspection Completed<?php } else { ?>Final Draft Survey (Completed)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->finial_survey_completed); ?></td>
                    </tr>
                    <tr style="border: 2px solid #03a9f4;">
                        <td style="width: 20%;">Cargo Operation Commenced</td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports->cargo_commenced); ?></td>
                        <td style="width: 20%;">Cargo Operation Completed</td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports->cargo_completed); ?></td>
                    </tr>
                </table>
            </div>




        </td>
    </tr>
    <tr>
        <td>




            <div class="robdetails">
                <table style="width: 100%;" border="0">
                    <tr>
                        <td style="width: 50%;"><h6>ROB-Arrival</h6></td>
                        <td style="width: 50%;"><h6 style="margin-left: 2%;">ROB-Sailing</h6></td>
                    </tr>
                </table>
                <!--                <div class="row" style="display:inline-block;">
                                    
                                    <div class="arrival" style="float:left;margin-right: 347px;"><h6>ROB-Arrival</h6></div>
                                    <div class="sailing" style="float:right;margin-left: 347px;"><h6>ROB-Sailing</h6></div>
                                </div>-->

                <table class="table" style="border: none;">
                    <tr style="border: none;">
                        <th style="width: 16.66%;">FO</th>
                        <th style="width: 16.66%;">DO</th>
                        <th style="width: 16.66%;">Fresh Water</th>
                        <th style="border: none;width: 1%;"></th>
                        <th style="width: 16.66%;">FO</th>
                        <th style="width: 16.66%;">DO</th>
                        <th style="width: 16.66%;">Fresh Water</th>
                    </tr>
                    <tr>
                        <td style="width: 16.66%;"><?php
                            if ($ports_rob->fo_arrival_quantity != '') {
                                    echo $ports_rob->fo_arrival_quantity
                                    ?><?=
                                    $ports_rob->fo_arrival_unit == 1 ? 'MT' : 'L';
                            }
                            ?></td>
                        <td style="width: 16.66%;"><?php
                            if ($ports_rob->do_arrival_quantity != '') {
                                    echo $ports_rob->do_arrival_quantity
                                    ?> <?=
                                    $ports_rob->do_arrival_unit == 1 ? 'MT' : 'L';
                            }
                            ?></td>
                        <td style="width: 16.66%;"><?php
                            if ($ports_rob->fresh_water_arrival_quantity != '') {
                                    echo $ports_rob->fresh_water_arrival_quantity
                                    ?> <?=
                                    $ports_rob->fresh_water_arrival_unit == 1 ? 'MT' : 'L';
                            }
                            ?></td>
                        <td style="border: none;width: 1%;"></td>
                        <td style="width: 16.66%;"><?php
                            if ($ports_rob->fo_sailing_quantity != '' && $ports_rob->fo_sailing_quantity != NULL) {
                                    echo $ports_rob->fo_sailing_quantity
                                    ?> <?=
                                    $ports_rob->fo_sailing_unit == 1 ? 'MT' : 'L';
                            }
                            ?></td>
                        <td style="width: 16.66%;"><?php
                            if ($ports_rob->do_sailing_quantity != '') {
                                    echo $ports_rob->do_sailing_quantity
                                    ?> <?=
                                    $ports_rob->do_sailing_unit == 1 ? 'MT' : 'L';
                            }
                            ?></td>
                        <td style="width: 16.66%;"><?php
                            if ($ports_rob->fresh_water_sailing_quantity != '') {
                                    echo $ports_rob->fresh_water_sailing_quantity
                                    ?> <?=
                                    $ports_rob->fresh_water_sailing_unit == 1 ? 'MT' : 'L';
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <td style="width: 16.66%;">ROB Received</td>
                        <td colspan="6" style="width: 83.3%;"><?= $ports_rob->rob_received ?></td>
                    </tr>
                </table>
            </div>



        </td>
    </tr>
    <tr>
        <td>




            <div class="draftdetails">
                <!--<h6>Drafts-Arrival/Departure</h6>-->
                <table style="width: 60%;" border="0">
                    <tr>
                        <td style="width: 50%;"><h6>Drafts-Arrival</h6></td>
                        <td style="width: 50%;"><h6 style="margin-left: 2%;">Drafts-Departure</h6></td>
                    </tr>
                </table>
                <table class="table" style="width:60%;border: none;">
<!--                    <tr>
                        <th colspan="2"style="width: 25%;">ARRIVAL</th>
                        <th colspan="2"style="width: 25%;">DEPARTURE</th>
                    </tr>-->
                    <tr>
                        <td style="width: 12.5%;">FWD</td>
                        <td style="width: 12.5%;"><?php if ($ports_draft->fwd_arrival_quantity != '') {
                                    echo $ports_draft->fwd_arrival_quantity . ' m';
                            } ?></td>
                        <td style="border: none;width: 1%;"></td>
                        <td style="width: 12.5%;">FWD</td>
                        <td style="width: 12.5%;"><?php if ($ports_draft->fwd_sailing_quantity != '') {
                                    echo $ports_draft->fwd_sailing_quantity . ' m';
                            } ?></td>
                    </tr>
                    <tr>
                        <td style="width: 12.5%;">AFT</td>
                        <td style="width: 12.5%;"><?php if ($ports_draft->aft_arrival_quantity != '') {
                                    echo $ports_draft->aft_arrival_quantity . ' m';
                            } ?></td>
                        <td style="border: none;"></td>
                        <td style="width: 12.5%;">AFT</td>
                        <td style="width: 12.5%;"><?php if ($ports_draft->aft_sailing_quantity != '') {
                                    echo $ports_draft->aft_sailing_quantity . ' m';
                            } ?></td>
                    </tr>
                </table>
            </div>



        </td>
    </tr>
    <tr>
        <td>



            <div class="portbreakdetails">
                <h6>Port Break Timing:</h6>
                <table class="table">
                    <tr>
                        <td style="width: 25%;">Tea Break</td>
                        <td style="width: 25%;">0200 - 0230</td>
                        <td style="width: 25%;">Lunch break</td>
                        <td style="width: 25%;">1300 - 1400</td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Tea Break</td>
                        <td style="width: 25%;">1000 - 1030</td>
                        <td style="width: 25%;">Dinner Break</td>
                        <td style="width: 25%;">2200 - 2300</td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Tea Break</td>
                        <td style="width: 25%;">1700 - 1730</td>

                    </tr>
                </table>
            </div>





        </td>
    </tr>
    <tr>
        <td>





            <div class="cargodetails">

                <h6>Cargo Details </h6>
                <table class="table">
                    <tr>
                        <th style="width: 50%;">Cargo Type</th>
                        <th style="width: 25%;">Loaded Quantity</th>
                        <th style="width: 25%;">B/L Quantity</th>
                    </tr>
                    <tr>
                        <td style="width: 50%;height: 13px;"><?= $ports_cargo->cargo_type ?></td>
                        <td style="width: 25%;"><?= $ports_cargo->loaded_quantity ?></td>
                        <td style="width: 25%;"><?= $ports_cargo->bl_quantity ?></td>
                    </tr>
                </table>
                <br/>
                <table class="table">
                    <tr>
                        <th style="width: 25%;">Remarks (if any):</th>
                        <td style="width: 75%;"><?= $ports_cargo->remarks ?></td>
                    </tr>
                    <tr>

                        <th style="width: 25%;">Stoppages / Delays:</th>  
                        <td style="width: 75%;height: 35px;"><?= $ports_cargo->stoppages_delays ?></td>
                    </tr>
                    <tr>

                        <th style="width: 25%;">Cargo Document</th>  
                        <td style="width: 75%;height: 35px;"><?= $ports_cargo->cargo_document ?></td>
                    </tr>
                    <tr>
                        <th style="width: 25%;">Master's Comments (if any)</th>
                        <td style="width: 75%;height: 80px;;"><?= $ports_cargo->masters_comment ?></td>
                    </tr>

                </table>
            </div>
            <br/>
<?php
if (!empty($port_stoppages)) {
        ?>
                    <div class="cargodetails">

                        <h6>Stoppages / Delays - Description </h6>
                        <table class="table"> 

                            <tr>
                                <th style="width: 33%;">From</th>
                                <th style="width: 33%;">To</th>
                                <th style="width: 34%;">Comment</th>
                            </tr>
                            <?php
                            foreach ($port_stoppages as $port_stoppage) {
                                    ?>
                                    <tr>
                                        <td style="width: 33%;height: 13px;"><?= Yii::$app->SetValues->DateFormate($port_stoppage->stoppage_from); ?></td>
                                        <td style="width: 33%;"><?= Yii::$app->SetValues->DateFormate($port_stoppage->stoppage_to); ?></td>
                                        <td style="width:34%;"><?= $port_stoppage->comment; ?></td>
                                    </tr>
        <?php } ?>
                        </table>
                        <br/>

                    </div>
<?php } ?>
            <br/>
            <!--            <div class="footer">
                            <div class="footer-left">
                                <h4> Master<br/>M/V Eastern View<br/><?= date('d/m/Y') ?></h4>
                            </div>
                            <div class="footer-right">
                                Agent
                            </div>
                        </div>-->

            <div class="footer">
                <div class="main-left">
                    <h4> Master<br/><br/><?= $appointment->vessel0->vessel_name ?><br/><br/>Dated:<?= date('d/m/Y') ?></h4>

                </div>
                <div class="main-right">
                    <table class="">
                        <h4>Agent</h4>
                    </table>
                </div>
                <br/>
            </div>
        </td></tr>
</tbody>
</table>
</body>

<style>
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

</style>
</html>
