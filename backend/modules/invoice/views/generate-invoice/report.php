<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\GenerateInvoice;
use common\models\InvoiceGenerateDetails;
use common\models\Currency;
use common\models\OnAccountOf;
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!--<html>-->
<!--<head>-->
<!--        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title></title>-->
<link rel="stylesheet" href="<?= Yii::$app->homeUrl ?>/css/pdf.css">
<style type="text/css">

        @media print {
                thead {display: table-header-group;}
                .main-tabl{width: 100%}
                tfoot {display: table-footer-group}
                /*tfoot {position: absolute;bottom: 0px;}*/
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
                text-align: center;
                margin-top: 18px;
        }

</style>
<!--</head>-->
<!--<body>-->
<table class="main-tabl" border="0" id="pdf">
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
                                <div class="heading" style="margin-bottom: 8px;"><?= OnAccountOf::findOne($invoice->on_account_of)->invoice_name; ?></div>
                                <div class="heading-top" style="margin-bottom: 80px;">
                                        <div class="main-left">
                                                <table class="tb2">
                                                        <tr>
                                                                <td>TO </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 212px;"><?= $invoice->to_address; ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                        <div class="main-right">
                                                <table class="tb2">
                                                        <tr>
                                                                <td>Invoice No </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 200px"><?= $invoice->invoice_number; ?></td>
                                                        </tr>

                                                        <tr>
                                                                <td>Date </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 200px"><?= Yii::$app->SetValues->DateFormate($invoice->date); ?></td>
                                                        </tr>

                                                        <tr>
                                                                <td>Ops ID </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 200px"><?= $invoice->oops_id ?></td>
                                                        </tr>
                                                        <tr>
                                                                <td>Customer Code </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 200px"><?= $invoice->customer_code ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                </div>
                        </td>
                </tr>
                <tr>
                        <td>
                                <div class="Invoice-list" style="margin-bottom: 15px;">
                                        <table class="table">
                                                <tr>
                                                        <th>ON ACCOUNT OF</th>
                                                        <th>JOB</th>
                                                        <th>PAYMENT TERMS</th>
                                                        <th>DOC NO</th>
                                                </tr>
                                                <tr>
                                                        <td>
                                                                <?= OnAccountOf::findOne($invoice->on_account_of)->on_account_of; ?>
                                                        </td>
                                                        <td>
                                                                <?php
                                                                if ($invoice->job == 1) {
                                                                        echo 'SERVICE / ATTENDANCE';
                                                                } elseif ($invoice->job == 2) {
                                                                        echo 'AGENTS / ATTENDANCE';
                                                                }
                                                                ?>
                                                        </td>
                                                        <td>
                                                                <?php
                                                                if ($invoice->payment_terms == 1) {
                                                                        echo 'Cash';
                                                                } elseif ($invoice->payment_terms == 2) {
                                                                        echo 'Cheque';
                                                                }
                                                                ?>
                                                        </td>
                                                        <td><?= $invoice->doc_no ?></td>
                                                </tr>
                                        </table>
                                </div>
                        </td>
                </tr>

                <tr>
                        <td>
                                <div class="Invoice-list">
                                        <table class="table">
                                                <tr>
                                                        <th>Slno</th>
                                                        <th>DESCRIPTION</th>
                                                        <th>QUANTITY</th>
                                                        <th>UNIT PRICE</th>
                                                        <th>TOTAL</th>
                                                </tr>
                                                <?php
                                                $k = 0;
                                                $i = 0;
                                                $grand_total = 0;
                                                $currency = Currency::findOne(['id' => 1]);
                                                foreach ($invoice_details as $value) {
                                                        $i++;
                                                        ?>
                                                        <tr>
                                                                <td><?= $i ?></td>
                                                                <td>
                                                                        <?= $value->description ?><br/>
                                                                        <span style="font-style: italic;"><?= $value->comments ?></span>
                                                                </td>
                                                                <td><?= $value->qty ?></td>
                                                                <td><?= $value->unit_price ?></td>
                                                                <td><?= $value->total ?></td>
                                                        </tr>
                                                        <?php
                                                        $grand_total += $value->total;
                                                        $k++;
                                                }
                                                for ($j = $k; $j <= 10; $j++) {
                                                        ?>
                                                        <tr>
                                                                <td style="height: 16px;"></td>
                                                                <td style="height: 16px;"></td>
                                                                <td style="height: 16px;"></td>
                                                                <td style="height: 16px;"></td>
                                                                <td style="height: 16px;"></td>
                                                        </tr>
                                                <?php }
                                                ?>
                                                <tr>
                                                        <td colspan="3" style="text-align:left;font-size: 11px;font-style: italic;color: red !important;"><?= $invoice->remarks ?></td>
                                                        <td style="text-align:right;font-size: 13px;font-weight: bold;">Total</td>
                                                        <td style="font-size: 10px;font-weight: bold;">
                                                                <?php
                                                                if ($invoice->currency == 1) {
                                                                        echo 'AED ' . Yii::$app->SetValues->NumberFormat($grand_total);
                                                                } else {

                                                                        echo 'USD ' . Yii::$app->SetValues->NumberFormat($grand_total);
                                                                }
                                                                ?>
                                                        </td>
                                                </tr>
                                        </table>
                                </div>
                        </td>
                </tr>
                <tr>
                        <td>
                                <div class="amount-word" style="margin-top: 75px;">
                                        <?php if ($invoice->currency == 1) { ?>
                                                <p style="font-size: 12px;font-weight: bold;font-style: italic;">AED <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords($grand_total)) . ' Only'; ?> </p>
                                        <?php } else {
                                                ?>

                                                <p style="font-size: 12px;font-weight: bold;font-style: italic;">USD <?php echo ucwords(Yii::$app->NumToWord->ConvertNumberToWords($grand_total, 'USD')) . ' Only'; ?> </p>
                                        <?php }
                                        ?>


                                </div>
                        </td>
                </tr>
                <?php
                if ($invoice->bank_details == 1) {
                        ?>
                        <tr>
                                <td>
                                        <div class="bank-details" style="float:right;margin-top: 75px;">

                                                <table>
                                                        <tr>
                                                                <td style="font-size: 12px;font-weight: bold;">Name</td>
                                                                <td>:</td>
                                                                <td style="font-size: 12px;font-weight: bold;">EMPEROR SHIPPING LINES LLC</td>
                                                        </tr>
                                                        <tr>
                                                                <td style="font-size: 12px;font-weight: bold;">Bank Name</td>
                                                                <td>:</td>
                                                                <td style="font-size: 12px;font-weight: bold;">Bank Of Baroda</td>
                                                        </tr>
                                                        <tr>
                                                                <td style="font-size: 12px;font-weight: bold;">Branch</td>
                                                                <td>:</td>
                                                                <td style="font-size: 12px;font-weight: bold;">Ras Al Khaimah, UAE</td>
                                                        </tr>
                                                        <tr>
                                                                <td style="font-size: 12px;font-weight: bold;">Acct No</td>
                                                                <td>:</td>
                                                                <td style="font-size: 12px;font-weight: bold;">90050200004102</td>
                                                        </tr>
                                                        <tr>
                                                                <td style="font-size: 12px;font-weight: bold;">IBAN No</td>
                                                                <td>:</td>
                                                                <td style="font-size: 12px;font-weight: bold;">AE150110090050200004102</td>
                                                        </tr>
                                                        <tr>
                                                                <td style="font-size: 12px;font-weight: bold;">Swift</td>
                                                                <td>:</td>
                                                                <td style="font-size: 12px;font-weight: bold;">BARBAEADRAK</td>
                                                        </tr>
                                                </table>

                                        </div>
                                </td>
                        </tr>

                        <?php
                }
                ?>

        </tbody>

        <tfoot>
                <tr>
                        <td style="width:100%">
                                <div class="footer">
                                        <div class="heading" style="margin-bottom: 25px;">THANK YOU FOR YOUR BUSINESS!</div>
                                        <div class="" style="display:inline-block;width:100%;">
                                                <div style="width:40%;float: left;">
                                                        <p style="text-align: left;">Emperor Shipping Lines LCC<br/>P.O.Box-328231<br/>Tel:+971 2689676/+971 7 268 9626<br/>Fax:+917 7 268 9677<br/>Email:opsrak@emperor.ae</p>

                                                </div>
                                                <div style="width:20%;float: left;">
                                                        <p style="padding-top: 40px;">www.emperor.ae</p>
                                                </div>
                                                <div style="width:40%;float: right;">
                                                        <p style="text-align: left;padding-left: 135px;">Port Office:Shipping Agent Bldg<br/>Ground Floor,Room:10<br/>Saqr Port Authority,Ras Al Khaimah,UAE<br/>Main Office:RAK Medical Centre Bldg<br/>Floor II,Room 06,Al Shaam,RAK,UAE</p>
                                                </div>
                                        </div>
                                </div>
                        </td>
                </tr>
        </tfoot>

</table>

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

<!--</html>-->
<div class="print">
        <button onclick="printContent('pdf')" style="font-weight: bold !important;">Print</button>
        <button onclick="window.close();" style="font-weight: bold !important;">Close</button>
</div>

