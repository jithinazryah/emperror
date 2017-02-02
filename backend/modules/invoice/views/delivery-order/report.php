<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\GenerateInvoice;
use common\models\InvoiceGenerateDetails;
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
        .Invoice-list span{
                padding: 0px 70px;
                border-bottom: 1px solid black;
                font-weight: bold;
                margin: 23px 0px;
        }
        .middle-content{
                width: 100%;
                display: inline-block;
                font-size: 15px;
                margin-top: 60px;
        }
        .middle-content p{
                text-align: left;
                font-size: 8px;
                margin: 0px !important;
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
                                <div class="heading" style="margin-bottom: 8px;">DELIVERY ORDER</div>
                                <div class="heading-top" style="margin-bottom: 50px;">
                                        <div class="main-left">
                                                <table class="tb2">
                                                        <tr>
                                                                <td>TO </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 212px;"><?= $order->to; ?></td>
                                                        </tr>
                                                </table>
                                        </div>
                                        <div class="main-right">
                                                <table class="tb2">
                                                        <tr>
                                                                <td>Ref. No </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 200px"><?= $order->ref_no; ?></td>
                                                        </tr>

                                                        <tr>
                                                                <td>Date </td> <td style="width: 50px;text-align: center">:</td>
                                                                <td style="max-width: 200px"><?= Yii::$app->SetValues->DateFormate($order->arrived_on); ?></td>
                                                        </tr>

                                                </table>
                                        </div>
                                </div>
                        </td>
                </tr>
        <br/>
        <tr>
                <td>
                        <div class="Invoice-list">
                                <p style="line-height: 30px;font-size: 11px;">Please deliver to M/s.  <span><?= $order->name; ?></span>  of P.O. Box  <span><?= $order->po_box; ?></span>
                                        the undermentiond packages arrived from <span><?= $order->arrived_from; ?></span> on <span><?= date('d-M-Y', strtotime($order->arrived_on)); ?></span>
                                        per vessel <span><?= $order->vessel_name; ?></span> Voyage no <span><?= $order->voyage_no; ?></span>
                                </p>

                        </div>
                </td>
        </tr>

        <tr>
                <td>
                        <div class="heading" style="margin-bottom: 12px;font-weight: normal;">PARTICULARS DECLARED BY SHIPPERS</div>
                        <div class="Invoice-list">
                                <table class="table">
                                        <tr>
                                                <th>Sl No</th>
                                                <th>BL/No</th>
                                                <th>Marks & NUmbers</th>
                                                <th>No of Packages or Pieces and Description of Goods</th>
                                        </tr>
                                        <?php
                                        $i = 0;
                                        $grandtotal = 0;
                                        foreach ($order_details as $value) {
                                                $i++;
                                                ?>
                                                <tr>
                                                        <td><?= $i ?></td>
                                                        <td><?= $value->bl_no ?></td>
                                                        <td><?= $value->marks_numbers ?></td>
                                                        <td><?= $value->description ?></td>
                                                </tr>
                                                <?php
                                                $grandtotal += $value->total;
                                        }
                                        ?>
                                        <tr>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight:bold;font-size:10px">Total</td>
                                                <td style="font-weight:bold;font-size:10px"><?= Yii::$app->SetValues->NumberFormat($grandtotal); ?> MT</td>
                                        </tr>
                                </table>
                        </div>
                        <div class="note">
                                <p style="font-size: 10px;">NOTE : Goods are lightered on the same terms and conditions as the Ocean carrier's Bill of Lading</p>
                        </div>
                </td>
        </tr>
        <tr>
                <td>
                        <div class="middle-content">
                                <div class="" style="display:inline-block;width:100%;">
                                        <div style="width:40%;float: left;">
                                                <p style="text-align: left;">CARGO DISCHARGED AT MINA SAQR, RAS AL KHAIMAH</p>

                                        </div>
                                        <div style="width:40%;float: right;">
                                                <p style="padding-left: 135px;">For Emperor Shipping Lines LLC<br/>(As Agents Only)<br/></p>
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

