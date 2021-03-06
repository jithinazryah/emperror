<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubServices;
use common\models\Appointment;
use common\models\EstimatedProforma;
use common\models\Debtor;
use common\models\PortCallData;
use common\models\Vessel;
use common\models\Ports;
use common\models\CloseEstimate;
use common\models\Services;
use common\models\InvoiceType;
use common\models\Currency;
use common\models\EstimateReport;
use common\models\InvoiceNumber;
use common\models\FundingAllocation;
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
                        body h6,h1,h2,h3,h4,h5,p,b,tr,td,span,th,div{
                                color:#525252 !important;
                        }
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
                footer {
                        width: 100%;
                        position: absolute;
                        bottom: 0px;
                }
        </style>
        <!--    </head>
            <body >-->
        <table border ="0"  class="main-tabl" border="0">
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
                                        <div class="heading">VOYAGE DISBURSEMENT ACCOUNT</div>
                                        <div class="closeestimate-content">
                                                <table border ="0"  class="table tbl">
                                                        <tr>
                                                                <td rowspan="2" style="width: 50%;">
                                                                        <p>
                                                                                EMPEROR SHIPPING LINES LLC<br>
                                                                                Room 06 / Floor II; P.O.Box-328231<br>
                                                                                Near Saqr Port, RAK Medical Bldg, Al Shaam, <br>
                                                                                Ras Al Khaimah, UAE
                                                                        </p>
                                                                </td>
                                                                <?php
                                                                if ($principp != '') {
                                                                        $principal_id = $principp;
                                                                } else {
                                                                        $principal_id = $appointment->principal;
                                                                }
                                                                $model_report = $this->context->InvoiceGeneration($appointment->id, $principal_id);
                                                                ?>

                                                                <td style="width: 25%;">Invoice No : <?= $model_report->invoice_number ?>-<?= $model_report->sub_invoice ?></td>
                                                                <?php
                                                                if ($invoice_date != '') {
                                                                        $date_invoice = date('d-M-Y', strtotime($invoice_date));
                                                                } else {
                                                                        $date_invoice = '';
                                                                }
                                                                ?>
                                                                <td style="width: 25%;">Invoice Date : <?php echo $date_invoice; ?></td>
                                                        </tr>
                                                        <tr>
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
                                                                <td style="width: 25%;">EPDA Ref : <?php echo $ref_no; ?></td>
                                                                <td style="width: 25%;">Customer Code :
                                                                        <?php
                                                                        if ($principp != '') {
                                                                                echo $appointment->getClintCode($principp);
                                                                        } else {
                                                                                echo $appointment->getClintCode($appointment->principal);
                                                                        }
                                                                        ?>

                                                                </td>

                                                        </tr>
                                                        <tr>
                                                                <td rowspan="3" style="width: 50%;">
                                                                        <p>
                                                                                <?php
                                                                                if ($principp != '') {
                                                                                        echo $appointment->getInvoiceAddress($principp);
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
                                                                <?php
                                                                $principal_datas = CloseEstimate::find()->select('principal')->distinct()->where(['apponitment_id' => $appointment->id])->all();
                                                                foreach ($principal_datas as $principal_data) {
                                                                        if ($principal_data->principal != '') {
                                                                                $data_principal .= $principal_data->principal . ',';
                                                                        }
                                                                }
                                                                ?>
                                                                <td style="width: 25%;">Ops Reference : <?php echo $appointment->appointment_no . $this->context->oopsNo(rtrim($data_principal, ","), $principp); ?> </td>
                                                        </tr>
                                                        <?php
                                                        ?>
                                                        <tr>
                                                                <td style="width: 25%;">Port of Call : <?= $appointment->portOfCall->port_name ?> </td>
                                                                <td style="width: 25%;">Client Ref : <?= $appointment->client_reference ?></td>
                                                        </tr>
                                                        <tr>
                                                                <td style="width: 25%;">Arrival Date : <?php
                                                                        if ($ports->all_fast != '') {
                                                                                echo date("d-M-Y", strtotime($ports->all_fast));
                                                                        }
                                                                        ?></td>
                                                                <td style="width: 25%;">Sailing Date : <?php
                                                                        if ($ports->cast_off != '') {
                                                                                echo date("d-M-Y", strtotime($ports->cast_off));
                                                                        }
                                                                        ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                </td>
                        </tr>
                        <tr>
                                <td>
                                        <?php
                                        if ($invoice_type != 'all') {
                                                if ($principp != '') {
                                                        $close_estimates = CloseEstimate::find()
                                                                ->where(['apponitment_id' => $appointment->id, 'principal' => $principp])
                                                                ->orderBy(['(invoice_type)' => SORT_ASC])
                                                                ->all();
//                                                        $close_estimates = CloseEstimate::findAll(['apponitment_id' => $appointment->id, 'principal' => $principp])->orderBy(['invoice_type' => SORT_DESC]);
                                                } else {
                                                        $close_estimates = CloseEstimate::find()
                                                                ->where(['apponitment_id' => $appointment->id, 'principal' => $appointment->principal])
                                                                ->orderBy(['(invoice_type)' => SORT_ASC])
                                                                ->all();
//                                                        $close_estimates = CloseEstimate::findAll(['apponitment_id' => $appointment->id, 'principal' => $appointment->principal])->orderBy(['invoice_type' => SORT_DESC]);
                                                }
                                        } else {
                                                $close_estimates = CloseEstimate::findAll(['apponitment_id' => $appointment->id, 'principal' => $principp]);
                                        }
                                        ?>
                                        <div class="closeestimate-content">
                                                <h6 class="sub-heading">Disbursement Summary:</h6>
                                                <h6 class="sub-heading">Total Disbursement</h6>
                                                <table border ="0"  class="table tbl">
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
                                                                        <?php
                                                                        $incoice_data = InvoiceNumber::find()->where("FIND_IN_SET($close_estimate->id,estimate_id)")->one();
                                                                        ?>
                                                                        <td style="width: 25%;"><?php echo InvoiceType::findOne(['id' => $close_estimate->invoice_type])->invoice_type; ?><?php
                                                                                if ($incoice_data->sub_invoice != '') {
                                                                                        echo '-' . $incoice_data->sub_invoice;
                                                                                }
                                                                                ?></td>
                                                                        <td style="width: 25%;text-align:right;"><?= Yii::$app->SetValues->NumberFormat($close_estimate->fda); ?></td>
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
                                                                <td style="width: 25%;font-weight: bold;text-align:right;">AED <?= Yii::$app->SetValues->NumberFormat(round($grandtotal, 2)); ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                </td>
                        </tr>
                        <tr>
                                <td>
                                        <?php
                                        if ($principp != '') {
                                                $funds = FundingAllocation::findAll(['appointment_id' => $appointment->id, 'principal_id' => $principp]);
                                        } else {
                                                $funds = FundingAllocation::findAll(['appointment_id' => $appointment->id]);
                                        }
                                        $fundamount = 0;
                                        $flag = 0;
                                        $check_total = 0;
                                        $cash_total = 0;
                                        foreach ($funds as $fund) {

                                                if ($fund->payment_type == 1) {

                                                        $cash_total += $fund->amount;
                                                        if ($fund->fund_date != '') {
                                                                $date = date("d-m-Y", strtotime($fund->fund_date));
                                                        } else {
                                                                $date = '';
                                                        }
                                                } elseif ($fund->payment_type == 1) {
                                                        $flag = 1;
                                                        $check_total += $fund->amount;
                                                        $check_no = $fund->check_no;
                                                        if ($fund->fund_date != '') {
                                                                $date = date("d-m-Y", strtotime($fund->fund_date));
                                                        } else {
                                                                $date = '';
                                                        }
                                                }
                                                $fundamount += $fund->amount;
                                        }
                                        $totaloutstanding = $fundamount - $grandtotal;
                                        ?>
                                        <div class="closeestimate-Receipts">
                                                <h6 class="sub-heading">Total Receipts(Prefunding)</h6>

                                                <table border ="0"  class="table tbl">
                                                        <tr>
                                                                <th style="width: 75%;">Description </th>
                                                                <th style="width: 25%;">Amount</th>
                                                        </tr>
                                                        <tr>
                                                                <?php
                                                                if ($flag == 1) {
                                                                        ?>
                                                                        <td style="width: 75%;text-align:left;font-size:11px;"><?php if ($check_total != 0) { ?>Net Received on <?= $date ?> <?php if ($check_no != '') { ?>against cheque no: <b><?= $check_no ?><?php
                                                                                                }
                                                                                        } else {
                                                                                                ?>NIL PREFUNDING RECEIVED <?php } ?></b></td>
                                                                        <td style="width: 25%;font-size: 11px;">AED <?= Yii::$app->SetValues->NumberFormat(round(abs($check_total), 2)); ?></td>
                                                                        <?php
                                                                } else {
                                                                        ?>
                                                                        <td style="width: 75%;text-align:left;font-size: 11px;"><?php if ($cash_total != 0) { ?>Net Received on <?= $date ?><?php } else { ?>NIL PREFUNDING RECEIVED<?php } ?></td>
                                                                        <td style="width: 25%;font-size: 11px;">AED <?= Yii::$app->SetValues->NumberFormat(round(abs($cash_total), 2)); ?></td>
                                                                        <?php
                                                                }
                                                                ?>

                                                        </tr>
                                                </table>
                                        </div>

                                        <div class="closeestimate-content">
                                                <h6 class="sub-heading">Total Outstanding</h6>
                                                <table border ="0"  class="table tbl">
                                                        <tr>
                                                                <?php
                                                                if ($totaloutstanding < 0) {
                                                                        ?>
                                                                        <td style="width: 75%;text-align:right;">Total Due in our favour </td>
                                                                        <td style="width: 25%;font-weight: bold;text-align:right;">AED <?= Yii::$app->SetValues->NumberFormat(round(abs($totaloutstanding), 2)); ?></td>
                                                                        <?php
                                                                } else {
                                                                        ?>
                                                                        <td style="width: 75%;text-align:right;">Total Due in Your favour </td>
                                                                        <td style="width: 25%;font-weight: bold;text-align:right;">AED <?= Yii::$app->SetValues->NumberFormat(round(abs($totaloutstanding), 2)); ?></td>
                                                                        <?php
                                                                }
                                                                ?>

                                                        </tr>
                                                        <?php
                                                        $currency = Currency::findOne(['id' => 1]);
                                                        $usd = round(abs($totaloutstanding) * $currency->currency_value, 2);
                                                        ?>
                                                        <tr>
                                                                <td style="width: 75%;"></th>
                                                                <td style="width: 25%;font-weight: bold;text-align:right;">USD <?= Yii::$app->SetValues->NumberFormat($usd); ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                </td>
                        </tr>
                        <tr>
                                <td>
                                        <div class="bank">
                                                <p>Amount chargeable (in words)</p>
                                                <h6>UAE Dirhams <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords(round(abs($totaloutstanding), 2))) . ' Only'; ?> </h6>
                                                <h6>USD <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords($usd, 'USD')) . ' Only'; ?> </h6>
                                                <h6>Company's Bank Details:</h6>
                                                <div class="bank-left">
                                                        <table border ="0"  class="tbl3">
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
                                                        <table border ="0"  class="">
                                                                <tr>
                                                                        <td>Remarks </td> <td>:</td>
                                                                        <td>
                                                                                <?php
                                                                                if ($appointment->vessel_type == 1) {
                                                                                        echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                                                                } elseif ($appointment->vessel_type == 2) {
                                                                                        echo 'M/V - ' . Vessel::findOne($appointment->vessel)->vessel_name;
                                                                                } elseif ($appointment->vessel_type == 3) {
                                                                                        echo 'M/T - ' . Vessel::findOne($appointment->vessel)->vessel_name;
                                                                                } else {
                                                                                        echo Vessel::findOne($appointment->vessel)->vessel_name;
                                                                                }
                                                                                ?>
                                                                        </td>
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
        <div class="print" style="float:left;">
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
                        <a href="<?= Yii::$app->homeUrl ?>appointment/close-estimate/save-all-report?appintment_id=<?= $appointment->id ?>&&principal_id=<?= $principp ?>"><button onclick="" style="font-weight: bold !important;">Save</button></a>
                        <?php
                }
                ?>

        </div>
</div>
<!--</body>

</html>-->