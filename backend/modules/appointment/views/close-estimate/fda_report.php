<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubServices;
use common\models\Appointment;
use common\models\EstimatedProforma;
use common\models\Debtor;
use common\models\PortCallData;
use common\models\Vessel;
use common\models\CloseEstimate;
use common\models\Services;
use common\models\InvoiceType;
use common\models\Currency;
use common\models\Ports;
use common\models\EstimateReport;
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title></title>-->
<div id="print">
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl ?>/css/pdf.css">
    <style type="text/css">

        @media print {
            thead {display: table-header-group;}
            tfoot {display: block; position:absolute; bottom: 0;}
            .main-tabl{width: 100%}
        }
        @media screen{
            .main-tabl{
                width: 60%;
            }
        }
        .print{
            margin-top: 18px;
            margin-left: 434px;
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
                <img src="<?= Yii::$app->homeUrl ?>/images/logoleft.jpg" style="width: 90px;height: 75px;"/>

            </div>
            <div class="main-right">
                <img src="<?= Yii::$app->homeUrl ?>/images/logoright.jpg" style="width: 90px;height: 75px;"/>
            </div>
            <br/>
        </div>
        </th> 
        </tr> 

        </thead> 
        <tbody>
            <tr>
                <td>
                    <div class="heading">INVOICE</div>
                    <div class="closeestimate-content">
                        <table class="table tbl">
                            <tr>
                                <td rowspan="5" style="width: 50%; font-weight: bold;">
                                    <p>
                                        <?php
                                        echo $close_estimate->getInvoiceAddress($close_estimate->principal);
                                        ?>
                                    </p>
                                </td>
                                <td style="width: 25%;">Date</td>
                                <td style="width: 25%;"><?php echo date('d-M-y'); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">Invoice No</td>
                                <td style="width: 25%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">Customer Code</td>
                                <td style="width: 25%;"><?php
                                    echo $close_estimate->getClintCode($close_estimate->principal);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">Operational Ref</td>
                                <td style="width: 25%;"><?= $appointment->appointment_no ?></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">EPDA Ref</td>
                                <?php
                                $arr = ['1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L'];
                                $last_report_saved = EstimateReport::find()->orderBy(['id' => SORT_DESC])->where(['appointment_id' => $appointment->id])->All();
                                $c = count($last_report_saved);
                                if ($c == 0) {
                                        $ref_no = 'EMPRK-' . $appointment->id . '/' . date('Y');
                                } else {
                                        $ref_no = 'EMPRK-' . $appointment->id . $arr[$c] . '/' . date('Y');
                                }
                                ?>
                                <td style="width: 25%;"><?php echo $ref_no; ?></td>
                            </tr>

                        </table>
                    </div>
                    <br/>
                    <div class="">
                        <table>
                            <tr>
                                <td style="width: 25%;font-size: 11px;">Vessel</td>
                                <td colspan="3" style="width: 75%;font-size: 11px;">:<?php
                                    if ($appointment->vessel_type == 1) {
                                            echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                    } else {
                                            echo Vessel::findOne($appointment->vessel)->vessel_name;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%;font-size: 11px;">Port</td>
                                <td colspan="3" style="width: 75%;font-size: 11px;">:<?= Ports::findOne($appointment->port_of_call)->port_name; ?></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;font-size: 11px;"></td>
                                <td colspan="3" style="width: 75%;font-size: 11px;"></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;font-size: 11px;">Arrival Date</td>
                                <td style="width: 25%;font-size: 11px;">:<?= Yii::$app->SetValues->DateFormate($ports->eosp); ?></td>
                                <td style="width: 25%;font-size: 11px;">Sailing Date</td>
                                <td style="width: 25%;font-size: 11px;">:<?= Yii::$app->SetValues->DateFormate($ports->cast_off); ?></td>
                            </tr>
                        </table>
                    </div>
                    <br/>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="">
                        <table class="table tbl">
                            <tr>
                                <th style="width: 10%;">Sl No.</th>
                                <th style="width: 60%;">Particulars
                                </th>
                                <th style="width: 30%;">Amount</th>
                            </tr>
                            <tr>
                                <td style="width: 10%;">1</td>
                                <td style="width: 60%;font-size:11px;"><?php echo Services::findOne(['id' => $close_estimate->service_id])->service; ?>
                                    <br/>
                                    <p style="font-style:italic;font-weight: bold;"><?= $close_estimate->comment_to_fda ?></p>
                                </td>
                                <td style="width: 30%;font-weight: bold;"><?= Yii::$app->SetValues->NumberFormat($close_estimate->fda); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 10%;"></td>
                                <td style="width: 60%;text-align:right;font-weight: bold;">
                                    <br/>
                                    <p style="font-size:13px;">TOTAL</p>
                                    <br/>
                                </td>
                                <td style="width: 30%;font-weight: bold;font-size:11px;"><p style="text-align:right;">AED <?= Yii::$app->SetValues->NumberFormat($close_estimate->fda); ?></p>
                                    <p style="text-align:right;">E & OE</p>
                                </td>
                            </tr>

                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="amount-words">
                        <table>
                            <tr>
                                <td style="width: 25%;font-size:10px;font-weight: bold;">Amount in Words</td>
                                <td style="width: 75%;font-size:10px;font-weight: bold;">AED <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords($close_estimate->fda)) . ' Only'; ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>


            <tr>
                <td>
                    <div class="close-estimate-footer">
                        <div class="close-left">
                            <p>This is computer generated invoice</p>
                        </div>
                        <div class="close-right">
                            <div style="border: 1px solid black;">
                                <h6>for EMPEROR SHIPPING LINES LLC</h6>
                                <p class="signature">Authorised Signatory</p>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot> 
            <tr> 
                <td style="width:100%">
                    <div class="footer">
                        <span>
                            <p>
                                Emperor Shipping Lines LLC, P.O.Box-328231, Saqr Port, Al Shaam, Ras Al Khaimah, UAE
                            </p>
                            <p>
                                Tel: +971 7 268 9676 / Fax: +917 7 268 9677
                            </p>
                            <p>
                                www.emperor.ae
                            </p>
                        </span>
                    </div> 
                </td> 
            </tr> 

        </tfoot>
    </table>
</div>
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
<!--</body>

</html>-->