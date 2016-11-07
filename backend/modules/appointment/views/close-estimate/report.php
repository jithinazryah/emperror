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
                                <td rowspan="2" style="width: 50%; font-weight: bold;">
                                    <p>
                                        EMPEROR SHIPPING LINES LLC<br>
                                        Room 06 / Floor II; P.O.Box-328231<br>
                                        Near Saqr Port, RAK Medical Bldg, Al Shaam, <br>
                                        Ras Al Khaimah, UAE
                                    </p>
                                </td>
                                <td style="width: 25%;">Invoice No : </td>
                                <td style="width: 25%;">Invoice Date : <?php echo date('d-M-y'); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">EPDA Ref :</td>
                                <td style="width: 25%;">Customer Code :
                                    <?php
                                    if ($princip->principal != '') {
                                            echo $appointment->getClintCode($princip->principal);
                                    } else {
                                            echo $appointment->getClintCode($appointment->principal);
                                    }
                                    ?>

                                </td>

                            </tr>
                            <tr>
                                <td rowspan="3" style="width: 50%; font-weight: bold;">
                                    <p>
                                        <?php
                                        if ($princip->principal != '') {
                                                echo $appointment->getInvoiceAddress($princip->principal);
                                        } else {
                                                echo $appointment->getInvoiceAddress($appointment->principal);
                                        }
                                        ?>
                                    </p>
                                </td>
                                <td style="width: 25%;">Vessel Name : <?php
                                    if ($appointment->vessel_type == 1) {
                                            echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                    } else {
                                            echo Vessel::findOne($appointment->vessel)->vessel_name;
                                    }
                                    ?>
                                </td>
                                <td style="width: 25%;">Ops Reference :<?= $appointment->appointment_no ?> </td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">Port of Call :<?= $appointment->portOfCall->port_name ?> </td>
                                <td style="width: 25%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">Arrival Date :<?= Yii::$app->SetValues->DateFormate($ports->eosp); ?></td>
                                <td style="width: 25%;">Sailing Date :<?= Yii::$app->SetValues->DateFormate($ports->cast_off); ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if ($invoice_type != 'all'){
                    if ($princip->principal != '') {
                            $close_estimates = CloseEstimate::findAll(['apponitment_id' => $appointment->id, 'invoice_type' => $princip->invoice_type, 'principal' => $princip->principal]);
                    } else {
                            $close_estimates = CloseEstimate::findAll(['apponitment_id' => $appointment->id, 'invoice_type' => $princip->invoice_type]);
                    }
                    }else{
                            $close_estimates = CloseEstimate::findAll(['apponitment_id' => $appointment->id, 'principal' => $principp]);
                    }
                    ?>
                    <div class="closeestimate-content">
                        <h6>Disbursement Summary:</h6>
                        <h6>Total Disbursement</h6>
                        <table class="table tbl">
                            <tr>
                                <th style="width: 10%;">Sl No.</th>
                                <th style="width: 40%;">Particulars</th>
                                <th style="width: 25%;">Invoice Reference </th>
                                <th style="width: 25%;">Amount</th>
                            </tr>
                            <?php
                            $i = 0;
                            $grandtotal = 0;
                            foreach ($close_estimates as $close_estimate):
                                    $i++;
                                    ?>
                                    <tr>
                                        <td style="width: 10%;"><?= $i ?></td>
                                        <td style="width: 40%;"><?php echo Services::findOne(['id' => $close_estimate->service_id])->service; ?></td>
                                        <td style="width: 25%;"><?php echo InvoiceType::findOne(['id' => $close_estimate->invoice_type])->invoice_type; ?></td>
                                        <td style="width: 25%;"><?= Yii::$app->SetValues->NumberFormat($close_estimate->fda); ?></td>
                                        <?php
                                        $grandtotal += $close_estimate->fda;
                                        ?>
                                    </tr>
                                    <?php
                            endforeach;
                            ?>
                            <tr>
                                <td style="width: 10%;"></td>
                                <td  colspan="2"style="width: 65%;text-align:right;font-weight: bold;">Total</td>
                                <td style="width: 25%;font-weight: bold;">AED <?= Yii::$app->SetValues->NumberFormat($grandtotal); ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="closeestimate-Receipts">

                        <table class="table tbl">
                            <tr>
                                <th style="width: 75%;">Description </th>
                                <th style="width: 25%;">Amount</th>
                            </tr>
                            <tr>
                                <td style="width: 75%;">Receipts</td>
                                <td style="width: 25%;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="closeestimate-content">
                        <h6>Total Outstanding</h6>
                        <table class="table tbl">
                            <tr>
                                <td style="width: 75%;">Total Due in our favour </td>
                                <td style="width: 25%;font-weight: bold;">AED <?= Yii::$app->SetValues->NumberFormat($grandtotal); ?></td>
                            </tr>
                            <?php
                            $currency = Currency::findOne(['id' => 1]);
                            $usd = round($grandtotal * $currency->currency_value, 3);
                            ?>
                            <tr>
                                <td style="width: 75%;"></th>
                                <td style="width: 25%;font-weight: bold;">USD <?= Yii::$app->SetValues->NumberFormat($usd); ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="bank">
                        <p>Amount chargeable (in words)</p>
                        <h6>UAE Dirhams <?php echo ucfirst(Yii::$app->NumToWord->ConvertNumberToWords($grandtotal)) . ' Only'; ?> </h6>
                        <h6>USD <?php echo ucfirst(Yii::$app->NumToWord->ConvertNumberToWords($usd)) . ' Only'; ?> </h6>
                        <h6>Company's Bank Details:</h6>
                        <div class="bank-left">
                            <table class="tbl3">
                                <tr>
                                    <td>Name: </td> <td>:</td>
                                    <td>EMPEROR SHIPPING LINES LLC</td>
                                </tr>
                                <tr>
                                    <td>Bank Name </td> <td>:</td>
                                    <td>Bank Of Baroda</td>
                                </tr>
                                <tr>
                                    <td>Branch </td> <td>:</td>
                                    <td>Ras Al Khaimah, UAE</td>
                                </tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
                                <tr>
                                    <td>Acct No </td> <td>:</td>
                                    <td>90050200004102</td>
                                </tr>
                                <tr>
                                    <td>IBAN No </td> <td>:</td>
                                    <td>AE150110090050200004102</td>
                                </tr>
                                <tr>
                                    <td>Swift </td> <td>:</td>
                                    <td>BARBAEADRAK</td>
                                </tr>
                            </table>
                        </div>
                        <div class="bank-right">
                            <table class="">
                                <tr>
                                    <td>Remarks: </td> <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Vessel </td> <td>:</td>
                                    <td><?php
                                        if ($appointment->vessel_type == 1) {
                                                echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                        } else {
                                                echo Vessel::findOne($appointment->vessel)->vessel_name;
                                        }
                                        ?></td>
                                </tr>
                            </table>
                        </div>
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
            <tr>
                <td>
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

        </tbody>
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