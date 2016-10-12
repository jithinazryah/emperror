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

    </head>
    <body >
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
    <tr><td>
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
                        <td style="width: 30%;"></td>
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

            <div class="events">
                <?php
                $port = $this->context->portcallReport($ports, 'ports');
                uasort($port['ports'], 'cmp');
                function cmp($port, $b) {
                        return strtotime($port) < strtotime($b) ? -1 : 1;
                }
                if (!empty($port)) {
                        ?>
                        <h6>Events</h6>
                        <table class="table">
                            <?php
                            $flag = 0;
                            foreach ($port['ports'] as $key => $value) {
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
                            ?>

                        </table>
                        <?php
                }
                ?>




            </div>

            <div class="survey_cargo">
                <h6>Survey/Cargo Timings</h6>
                <table class="table">
                    <tr>
                        <td style="width: 20%;"><?php if($appointment->vessel_type == 3){ ?>Ullaging / Sampling Commenced<?php }else{ ?>Initial Draft Survey (Commenced)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->intial_survey_commenced); ?></td>
                        <td style="width: 20%;"><?php if($appointment->vessel_type == 3){ ?>Tank Inspection Commenced<?php }else{ ?>Final Draft Survey (Commenced)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->finial_survey_commenced); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;"><?php if($appointment->vessel_type == 3){ ?>Ullaging / Sampling Completed<?php }else{ ?>Initial Draft Survey (Completed)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->intial_survey_completed); ?></td>
                        <td style="width: 20%;"><?php if($appointment->vessel_type == 3){ ?>Tank Inspection Completed<?php }else{ ?>Final Draft Survey (Completed)<?php } ?></td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports_draft->finial_survey_completed); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Cargo Operation Commenced</td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports->cargo_commenced); ?></td>
                        <td style="width: 20%;">Cargo Operation Completed</td>
                        <td style="width: 30%;"><?= Yii::$app->SetValues->DateFormate($ports->cargo_completed); ?></td>
                    </tr>
                </table>
            </div>

            <div class="robdetails">
                <div class="row" style="display:inline-block;">
                    <div class="arrival" style="float:left;margin-right: 347px;"><h6>ROB-Arrival</h6></div>
                    <div class="sailing" style="float:right;margin-left: 347px;"><h6>ROB-Sailing</h6></div>
                </div>

                <table class="table">
                    <tr>
                        <th style="width: 16.66%;">FO</th>
                        <th style="width: 16.66%;">DO</th>
                        <th style="width: 16.66%;">Fresh Water</th>
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
                        <td colspan="5" style="width: 83.3%;"></td>
                    </tr>
                </table>
            </div>

            <div class="draftdetails">
                <h6>Drafts-Arrival/Departure</h6>
                <table class="table" style="width: 50%">
                    <tr>
                        <th colspan="2"style="width: 25%;">ARRIVAL</th>
                        <th colspan="2"style="width: 25%;">DEPARTURE</th>
                    </tr>
                    <tr>
                        <td style="width: 12.5%;">FWD</td>
                        <td style="width: 12.5%;"><?= $ports_draft->fwd_arrival_quantity ?></td>
                        <td style="width: 12.5%;">FWD</td>
                        <td style="width: 12.5%;"><?= $ports_draft->fwd_sailing_quantity ?></td>
                    </tr>
                    <tr>
                        <td style="width: 12.5%;">AFT</td>
                        <td style="width: 12.5%;"><?= $ports_draft->aft_arrival_quantity ?></td>
                        <td style="width: 12.5%;">AFT</td>
                        <td style="width: 12.5%;"><?= $ports_draft->aft_sailing_quantity ?></td>
                    </tr>
                </table>
            </div>

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
                    <h4> Master<br/><br/>M/V Eastern View<br/><br/>Dated:<?= date('d/m/Y') ?></h4>

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

</style>
</html>
