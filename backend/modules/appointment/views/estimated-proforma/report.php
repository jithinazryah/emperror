<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubServices;
use common\models\Appointment;
use common\models\EstimatedProforma;
use common\models\Debtor;
use common\models\ServiceCategorys;
use common\models\Services;
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
        <!--        <button onclick="window.print()">Print</button>-->
        <table class="main-tabl" border="0"> 
            <thead> 
                <tr> 
                    <th style="width:100%">
            <div class="header">
                <div class="main-left">
                    <img src="<?= Yii::$app->homeUrl ?>/images/logoleft.jpg" style="width: 100px;height: 100px;"/>
                    <table class="">
                        <tr>
                            <td>TO </td> <td style="width: 50px;text-align: center">:</td>
                            <td style="max-width: 200px"><?= $appointment->getInvoiceAddress($princip); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="main-right">
                    <img src="<?= Yii::$app->homeUrl ?>/images/logoright.jpg" style="width: 100px;height: 100px;"/>
                    <table class="">
                        <tr>
                            <td>Date </td> <td style="width: 50px;text-align: center">:</td>
                            <td style="max-width: 200px"><?= date("d/m/Y") ?></td>
                        </tr>
                        <tr>
                            <td>Client Code </td> <td style="width: 50px;text-align: center">:</td>
                            <td style="max-width: 200px"><?= $appointment->getClintCode($appointment->principal); ?></td>
                        </tr>
                    </table>
                </div>
                <br/>
            </div>
        </th> 
    </tr> 

</thead> 
<div class="main">
    <div class="header">
        <div class="main-left">
            <img src="<?= Yii::$app->homeUrl ?>/images/logoleft.jpg"/>
            <table class="">
                <tr>
                    <td>TO </td> <td style="width: 50px;text-align: center">:</td>
                    <td style="max-width: 200px"><?= $appointment->getInvoiceAddress($princip); ?></td>
                </tr>
            </table>
        </div>
        <div class="main-right">
            <img src="<?= Yii::$app->homeUrl ?>/images/logoright.jpg"/>
            <table class="">
                <tr>
                    <td>Date </td> <td style="width: 50px;text-align: center">:</td>
                    <td style="max-width: 200px"><?= date("d/m/Y") ?></td>
                </tr>
                <tr>
                    <td>Client Code </td> <td style="width: 50px;text-align: center">:</td>
                    <td style="max-width: 200px"><?= $appointment->getClintCode($appointment->principal); ?></td>
                </tr>
            </table>
        </div>
        <br/>
    </div>
    <div class="heading">ESTIMATED PORT COST</div>
    <div class="topcontent">
        <div class="topcontent-left">
            <table class="">
                <tr>
                    <td>Port </td> <td>:</td>
                    <td><?= $appointment->portOfCall->port_name ?></td>
                </tr>
                <tr>
                    <td>ETA </td> <td>:</td>
                    <td><?= $appointment->eta ?></td>
                </tr>
            </table>
        </div>
        <div class="topcontent-center">
            <table class="">
                <tr>
                    <td>Vessel </td> <td>:</td>
                    <td><?= $appointment->vessel0->vessel_name ?></td>
                </tr>
                <tr>
                    <td>Purpose </td> <td>:</td>
                    <td><?= $appointment->purpose0->purpose ?></td>
                </tr>
            </table>
        </div>
        <div class="topcontent-right">
            <table class="">
                <tr>
                    <td>Ref No </td> <td>:</td>
                    <td><?= $appointment->appointment_no ?></td>
                </tr>
                <tr>
                    <td>Ops no </td> <td>:</td>
                    <td>TBC</td>
                </tr>
            </table>
        </div>

    </div>
    <div style="height: 1000px;">
        <div class="content-header">
            <table class="table tbl">
                <tr>
                    <td colspan="2" style="width: 60%; font-weight: bold;">Service Category</td>
                    <td rowspan="2" style="width: 8%;">Unit Tons/No</td>
                    <td rowspan="2" style="width: 16%;"><b>Comments</b></td>
                    <td rowspan="2" style="width: 8%;">Unit Price</td>
                    <td style="width: 8%;">Amount</td>
                </tr>
                <tr>
                    <td style="width: 30%;">&nbsp;</td>
                    <td style="width: 30%;color: red;">Comments/Rate to category</td>
                    <td style="width: 10%;">AED</td>

                </tr>
            </table>
        </div>

        <div class="content-body">
            <?php
            $subepdatotal = 0;
            $service_categories = ServiceCategorys::find()->orderBy(['(sort_order)' => SORT_ASC])->all();
            foreach ($service_categories as $service_category) {
                    $estimates = EstimatedProforma::findAll(['apponitment_id' => $appointment->id, 'principal' => $princip, 'service_category' => $service_category->id]);
                    if (!empty($estimates)) {
                            ?>
                            <h6><?= $service_category->category_name ?></h6>
                            <?php
                            foreach ($estimates as $estimate) {
                                    $subcategories = SubServices::findAll(['estid' => $estimate->id]);
                                    if (!empty($subcategories)) {
                                            $subtotal = 0;
                                            ?>
                                            <table class="table">
                                                <?php
                                                foreach ($subcategories as $subcategory) {
                                                        ?>
                                                        <tr>
                                                            <td style="width: 30%;"><?= $subcategory->sub->sub_service ?></td>
                                                            <td style="width: 30%;"><?= $subcategory->rate_to_category ?></td>
                                                            <td style="width: 8%;"><?= $subcategory->unit ?></td>
                                                            <td style="width: 16%;"><?= $subcategory->comments ?></td>
                                                            <td style="width: 8%;"><?= $subcategory->unit_price ?></td>
                                                            <td style="width: 8%;font-weight: bold;"><?= $subcategory->total ?></td>
                                                            <?php
                                                            $subtotal += $subcategory->total;
                                                            ?>
                                                        </tr>
                                                        <?php
                                                }
                                                $grandtotal+=$subtotal;
                                                ?>
                                                <tr>
                                                    <td colspan="5" style="text-align: center;">Sub total:</td>
                                                    <td style="font-weight: bold;">AED <?= $subtotal ?></td>
                                                </tr>
                                            </table>
                                    <?php } else {
                                            ?>

                                            <table class="table">
                                                <tr>
                                                    <td style="width: 30%;"><?= $estimate->service->service ?></td>
                                                    <td style="width: 30%;"></td>
                                                    <td style="width: 8%;"><?= $estimate->unit ?></td>
                                                    <td style="width: 16%;"><?= $estimate->comments ?></td>
                                                    <td style="width: 8%;"><?= $estimate->unit_rate ?></td>
                                                    <td style="width: 8%;font-weight: bold;"><?= $estimate->epda ?></td>
                                                    <?php
                                                    $epdasubtotal += $estimate->epda;
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: center;">Sub total:</td>
                                                    <td style="font-weight: bold;">AED <?= $epdasubtotal ?></td>
                                                </tr>
                                            </table>

                                            <?php
                                            $subepdatotal += $estimate->epda;
                                    }
                            }
                    }
            }
            ?>
        </div>
        <!--            <div class="content">
                        <h6>Vessel Expenses</h6>
                        <table class="table">
                            <tr>
                                <td style="width: 30%;">1</td>
                                <td style="width: 30%;">2</td>
                                <td style="width: 8%;">3</td>
                                <td style="width: 16%;">4</td>
                                <td style="width: 8%;">5</td>
                                <td style="width: 8%;">6</td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align: center;">Sub total:</td>
                                <td>AED125</td>
                            </tr>
                        </table>
                    </div>
                    <div class="content">
                        <h6>Agency Charges</h6>
                        <table class="table">
                            <tr>
                                <td style="width: 30%;">1</td>
                                <td style="width: 30%;">2</td>
                                <td style="width: 8%;">3</td>
                                <td style="width: 16%;">4</td>
                                <td style="width: 8%;">5</td>
                                <td style="width: 8%;">6</td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align: center;">Sub total:</td>
                                <td>AED125</td>
                            </tr>
                        </table>
                    </div>-->
        <br/>
        <div class="grandtotal">
            <table class="table">
                <tr>
                    <td style="width: 84%; text-align: center;"><b>Grand Total Estimate</b></td>
                    <td style="width: 8%;">USD 123456</td>
                    <td style="width: 8%;font-weight: bold;">AED <?= $grandtotal + $subepdatotal; ?></td>
                </tr>
            </table>
        </div>
        <!--<br/>-->
        <div class="content">
            <p class="para-heading">- Additional scope of work other than mentioned in the tarrif to be mutually agreed between two parties prior initiation of service.</p>
<!--            <p class="para-content">
                Please note that this is a pro-forma disbursement account only. It is intended to be an estimate of the actual disbursement account and is for guidance purposes only. 
                Whilst Emperor Shipping Lines does take every care to ensure that the figures and information contained in the pro-forma disbursement account are as accurate as possibles
                ,the actual disbursement account may, and often does, for various reasons beyond our control, vary from the pro-forma disbursement account. 
            </p>-->

            <p class="para-content">
                This duty exists regardless of any difference between the figures in this pro-forma disbursement account and the actual disbursement account.
            </p>
            <p class="para-content">
                To facilitate easy tracking, please include the ref number, vessel name & ETA on remittance advices and all correspondence.
                This will reduce the chance of delays due to mis-identification of funds
            </p>
            <p class="para-content1">
                All services from Third Party Service Providers are performed in accordance with the relevant service providers Standard Trading Terms & Conditions,
                which a copy can be obtained on request from our office.
            </p>

            <p class="para-content1">
                All services are performed in accordance with the ESL Standard Trading Terms & Conditions which can be viewed at www.emperor.ae and a copy
                of which is available on request.
            </p>
        </div>
    </div>
    <div class="bankdetails">
        <h3>Bank Details </h3>
        <table class="table">
            <tr>
                <td colspan="2" style="text-align: center;font-weight: bold;">CURRENCIES ACCEPTED : USD / AED / EURO / GBP</td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">NAME</td>
                <td></td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">A/C NO</td>
                <td></td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">IBAN</td>
                <td></td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">BANK NAME</td>
                <td></td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">SWIFT</td>
                <td></td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">BRANCH</td>
                <td></td>
            </tr>
            <tr>
                <td style="width:30%;text-align: left;">Correspondent Bank in USA</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="bankdetails">
        <div class="bankdetails-left">
            <h5>Account Manager</h5> 
            <a href="#" style="color: #03a9f4;">accrak@emperor.ae</a>
            <h5>T: +971 7 268 9076(Ext: 205)</h5>
        </div>
    </div>
    <div class="address">
        <h3>Address </h3>
        <table class="table">
            <tr>
                <td colspan="2" style="text-align: center;font-weight: bold;text-decoration: underline;">GENERAL ADDRESS</td>
            </tr>
            <tr>
                <td style="width:50%;">
                    <h4>Main Office-RAS AL KHAIMAH</h4>
                    <p>EMPEROR SHIPPING LINES LLC <br/>P.O.BOX - 328231 <br/> ROOM NO: 06 /FLOOR 11 <br/> RAK MEDICAL CENTRE BLDG <br/> NEAR MINA SAQR ALSHAAM <br/> RAS AL KHAIMAH, UAE</p>
                </td>
                <td style="width:50%;">
                    <h4>Port Office-RAS AL KHAIMAH</h4>
                    <p>EMPEROR SHIPPING LINES LLC <br/>P.O.BOX - 328231 <br/> ROOM NO: 10A / GROUND FLOOR <br/> SHIPPING AGENCY BUILDING <br/> SAQR PORT, KHOR KHWAIR <br/> RAS AL KHAIMAH, UAE</p>
                </td>
            </tr>
            <tr>
                <td colspan="2"><h4 style="text-align: center;font-weight: bold;text-decoration: underline;">CONTACT DETAILS</h4>
                    <p>TEL: +971 7 268 9670 (24x7) <br/> FAX: +971 7 208 9677 <br/> COMMON EMAIL:<a href="#" style="color: #03a9f4;">OPSARAK@EMPEROR.AE</a></p>
                </td>
            </tr>
            <tr>
                <td style="width:50%;">
                    Emergency Contact Details
                </td>
                <td style="width:50%;">
                    <p>Mr.Nidhin Wails (Ops Manager) : + 971 55 300 1535</p>
                    <p>Email :<a href="#" style="padding-left: 114px;color: #03a9f4;">nidhin.wails@emperor.ae</a></p>
                    <p>Mr.Alen John (Branch Manager) : + 971 55 300 1534</p>
                    <p>Email :<a href="#" style="padding-left: 114px; color: #03a9f4;">alenp.john@emperor.ae</a></p>
                </td>
            </tr>
        </table>
    </div>
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


</div>
</table>
</body>
</html>
