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
use common\models\InvoiceNumber;
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
                        tfoot {display: table-footer-group}
                        /*tfoot {position: absolute;bottom: 0px;}*/
                        .main-tabl{width: 100%}
                        .footer {position: fixed ; left: 0px; bottom: 20px; right: 0px; font-size:10px; }
                        body{
                                color:#525252;
                        }
                }
                @media screen{
                        .main-tabl{
                                width: 60%;
                        }
                        body{
                                color:#525252;
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
                                        <br/>
                                        <div class="close-estimate-heading-top" style="margin-bottom:78px;">
                                                <div class="main-left">
                                                        <table class="tb2">
                                                                <tr>
                                                                        <td style="max-width: 405px">
                                                                                <?php
                                                                                echo Debtor::findOne($princip->principal)->invoicing_address;
                                                                                ?>
                                                                        </td>
                                                                </tr>
                                                        </table>
                                                </div>
                                                <div class="main-right">
                                                        <table class="tb2">
                                                                <tr>
                                                                        <td>Date </td> <td style="width: 50px;text-align: center">:</td>
                                                                        <td style="max-width: 200px"><?= date("d-M-Y") ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td>Invoice No </td> <td style="width: 50px;text-align: center">:</td>
                                                                        <?php
                                                                        $arr1 = ['1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L'];
                                                                        $last = InvoiceNumber::find()->orderBy(['id' => SORT_DESC])->where(['invoice_type' => $princip->invoice_type])->one();
                                                                        if (!empty($last)) {
                                                                                $last_invoice_report_saved = InvoiceNumber::find()->select('estimate_id')->distinct()->orderBy(['estimate_id' => SORT_ASC])->where(['appointment_id' => $appointment->id, 'invoice_type' => $princip->invoice_type])->all();
                                                                                $key = count($last_invoice_report_saved);
                                                                                $model_report = $this->context->GenerateInvoiceNo(implode('_', $est_id));
//                                                                                $model_report = backend\modules\appointment\controllers\CloseEstimateController::GenerateInvoiceNo(implode('_', $est_id));
                                                                                if ($key == 0) {

                                                                                        $invoice_no = InvoiceType::findOne($princip->invoice_type)->invoice_type . ' ' . $model_report->invoice_number;
                                                                                } else {
                                                                                        $invoice_no = InvoiceType::findOne($princip->invoice_type)->invoice_type . ' ' . $model_report->invoice_number . $arr1[$key];
                                                                                }
                                                                        } else {
//                                                                                $model_report = $this->context->GenerateInvoiceNo(implode('_', $est_id));
                                                                                $invoice_no = InvoiceType::findOne($princip->invoice_type)->invoice_type . ' ' . $model_report->sub_invoice;
                                                                        }
//
                                                                        ?>
                                                                        <td style="max-width: 200px">
                                                                                <?php echo $invoice_no; ?>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                        <td>Customer Code </td> <td style="width: 50px;text-align: center">:</td>
                                                                        <td style="max-width: 200px">
                                                                                <?php
                                                                                echo Debtor::findOne(['id' => $princip->principal])->principal_id;
                                                                                ?>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                        <td>Operational Ref</td> <td style="width: 50px;text-align: center">:</td>
                                                                        <td style="max-width: 200px"><?= $appointment->appointment_no ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td>EPDA Ref </td> <td style="width: 50px;text-align: center">:</td>
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
                                                                        <td style="max-width: 200px"><?php echo $ref_no; ?></td>
                                                                </tr>
                                                        </table>
                                                </div>
                                        </div>
                                        <br/>
                                        <div class="close-estimate-vessel" style="margin-bottom: 32px;">
                                                <div class="main-left">
                                                        <table class="tb2">
                                                                <tr>
                                                                        <td>Vessel </td> <td style="width: 50px;">:</td>
                                                                        <td style="max-width: 405px">
                                                                                <?php
                                                                                if ($appointment->vessel_type == 1) {
                                                                                        echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                                                                } else {
                                                                                        echo Vessel::findOne($appointment->vessel)->vessel_name;
                                                                                }
                                                                                ?>

                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                        <td>Port </td> <td style="width: 50px;">:</td>
                                                                        <td style="max-width: 405px">
                                                                                <?= Ports::findOne($appointment->port_of_call)->port_name; ?>
                                                                        </td>
                                                                </tr>

                                                        </table>
                                                </div>
                                                <div class="main-right">
                                                        <table class="tb2">
                                                                <tr>
                                                                        <td></td> <td style="width: 50px;text-align: center"></td>
                                                                        <td style="max-width: 200px"></td>
                                                                </tr>
                                                                <tr>
                                                                        <td></td> <td style="width: 50px;text-align: center"></td>
                                                                        <td style="max-width: 200px">

                                                                        </td>
                                                                </tr>

                                                        </table>
                                                </div>
                                        </div>
                                        <br/>
                                        <div class="close-estimate-attival-sailing" style="margin-bottom: 10px;">
                                                <div class="main-left">
                                                        <table class="tb2">
                                                                <tr>
                                                                        <td>Arrival Date </td> <td style="width: 50px;">:</td>
                                                                        <td style="max-width: 405px">
                                                                                <?php
                                                                                if ($ports->all_fast != '') {
                                                                                        echo date("d-M-Y", strtotime($ports->all_fast));
                                                                                }
                                                                                ?>
                                                                        </td>
                                                                </tr>
                                                        </table>
                                                </div>
                                                <div class="main-right">
                                                        <table class="tb2">
                                                                <tr>
                                                                        <td>Sailing Date </td> <td style="width: 50px;text-align: center">:</td>
                                                                        <td style="max-width: 200px">
                                                                                <?php
                                                                                if ($ports->cast_off != '') {
                                                                                        echo date("d-M-Y", strtotime($ports->cast_off));
                                                                                }
                                                                                ?>
                                                                        </td>
                                                                </tr>
                                                        </table>
                                                </div>
                                        </div>
                                        <br/>
                                        <hr/>
                                </td>
                        </tr>

                        <tr>
                                <td>
                                        <div class=""style="margin-bottom: 10px;margin-top: 26px;">
                                                <table style="width:100%">
                                                        <tr>
                                                                <th style="width: 10%;font-size: 10px;">Sl No.</th>
                                                                <th style="width: 60%;font-size: 10px;text-align: left;">Particulars
                                                                </th>
                                                                <th style="width: 30%;font-size: 10px;text-align: right;">Amount</th>
                                                        </tr>
                                                        <tr></tr>
                                                        <tr></tr>
                                                        <tr></tr>
                                                        <tr></tr>
                                                        <tr></tr>
                                                        <tr></tr>
                                                        <?php
                                                        $i = 0;
                                                        $grandtotal = 0;
                                                        foreach ($close_estimates as $close_estimate):
                                                                $i++;
                                                                ?>
                                                                <tr>
                                                                        <td style="width: 10%;text-align: center;font-size: 9px;"><?= $i ?></td>
                                                                        <td style="width: 60%;font-size:11px;text-align: left;font-size: 9px;"><?php echo Services::findOne(['id' => $close_estimate->service_id])->service; ?>
                                                                        </td>
                                                                        <td style="width: 30%;font-weight: bold;text-align: right;font-size: 9px;"><?= Yii::$app->SetValues->NumberFormat($close_estimate->fda); ?></td>
                                                                        <?php
                                                                        $grandtotal += $close_estimate->fda;
                                                                        ?>
                                                                </tr>
                                                                <tr>
                                                                        <td style="width: 10%;text-align: center;font-size: 9px;"></td>
                                                                        <td style="width: 60%;"><p style="font-style:italic;text-align: left;font-size: 9px;"><?= $close_estimate->comment_to_fda ?></p></td>
                                                                        <td style="width: 30%;font-weight: bold;text-align: right;font-size: 9px;"></td>
                                                                </tr>
                                                                <?php
                                                        endforeach;
                                                        ?>
                                                        <tr>
                                                                <td style="width: 10%;"></td>
                                                                <td style="width: 60%;text-align:center;font-weight: bold;">
                                                                        <br/>
                                                                        <p style="font-size:10px;">TOTAL</p>
                                                                        <br/>
                                                                </td>
                                                                <?php
                                                                $currency = Currency::findOne(['id' => 1]);
                                                                $usd = round($grandtotal * $currency->currency_value, 2);
                                                                ?>
                                                                <td style="width: 30%;font-weight: bold;font-size:8px;"><p style="text-align:right;">AED <?= Yii::$app->SetValues->NumberFormat(round($grandtotal, 2)); ?></p>
                                                                        <p style="text-align:right;">USD <?= Yii::$app->SetValues->NumberFormat($usd); ?></p>
                                                                        <p style="text-align:right;">E & OE</p>
                                                                </td>
                                                        </tr>

                                                </table>
                                        </div>
                                </td>
                        </tr>
                        <tr>
                                <td>
                                        <div class="amount-words" style="margin-bottom: 9px;">
                                                <table style="width:100%;">
                                                        <tr>
                                                                <td style="width: 15%;font-size:9px;font-weight: bold;">Amount in Words</td>
                                                                <td style="width: 85%;font-size:9px;font-weight: bold;">AED  <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords(round($close_estimate->fda, 2))) . ' Only'; ?></td>
                                                        </tr>
                                                        <tr>
                                                                <td style="width: 15%;font-size:9px;font-weight: bold;"></td>
                                                                <td style="width: 85%;font-size:9px;font-weight: bold;">USD  <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords($usd, 'USD')) . ' Only'; ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                </td>
                        </tr>


                        <tr>
                                <td>
                                        <div class="close-estimate-footer" style="margin-top: 100px;">
                                                <div class="close-left">
                                                        <p>This is computer generated invoice</p>
                                                </div>
                                                <div class="close-right">
                                                        <div>
                                                                <h6 style="font-size: 10px;padding-left: 150px;">for EMPEROR SHIPPING LINES LLC</h6>
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
                                                                <span class="footer-red">Emperor</span> <span  class="footer-blue1">Shipping Lines LLC</span> &#8208; Ras Al Khaimah (Br)| P.O.Box-328231 |Ops Email: <span class="footer-blue2">opsrak@emperor.ae</span> |Accts Email: <span class="footer-blue2">accrak@emperor.ae</span>
                                                        </p>
                                                        <p>
                                                                www.emperor.ae
                                                        </p>
                                                        <p>
                                                                Main Office: RAK Medical Centre Bldg |Floor II, Room 06 | Al Shaam, RAK, UAE | Tel: +971 7 268 9676 |Fax: +971 7 268 9677
                                                        </p>
                                                        <p>
                                                                Port Office: Shipping Agents Bldg |Ground Floor, Room: 10 A | Saqr Port Authority, Ras Al Khaimah, UAE | Tel: +971 7 268 9626
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
        <?php
        if ($print) {
                ?>
                <button onclick="printContent('print')" style="font-weight: bold !important;">Print</button>
                <?php
        }
        ?>
        <button onclick="window.close();" style="font-weight: bold !important;">Close</button>
        <?php
        if ($save) {
                ?>
                <a href="<?= Yii::$app->homeUrl ?>appointment/close-estimate/save-report?estid=<?php echo implode('_', $est_id) ?>"><button onclick="" style="font-weight: bold !important;">Save</button></a>
                <?php
        }
        ?>
</div>
<!--</body>

</html>-->