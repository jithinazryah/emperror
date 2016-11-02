<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\SubServices;
use common\models\Appointment;
use common\models\EstimatedProforma;
use common\models\Debtor;
use common\models\ServiceCategorys;
use common\models\Services;
use common\models\Vessel;
?>
<!DOCTYPE html>

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
        .table td {
            border: 1px solid black;
            font-size: 9px !important;
            text-align: center;
            padding: 3px;
        }
        .print{
            margin-top: 18px;
            margin-left: 532px;
        }
        .save{
            margin-top: 18px;
            margin-left: 6px !important;
        }
    </style>
    <table class="main-tabl" border="0" > 
        <thead> 
            <tr> 
                <th style="width:100%">
        <div class="header">
            <div class="main-left">
                <img src="<?= Yii::$app->homeUrl ?>/images/logoleft.jpg" style="width: 100px;height: 100px;"/>
            </div>
            <div class="main-right">
                <img src="<?= Yii::$app->homeUrl ?>/images/logoright.jpg" style="width: 100px;height: 100px;"/>
            </div>
            <br/>
        </div>
        </th> 
        </tr> 

        </thead> 
        <tbody>
            <tr>
                <td>
                    <div class="heading-top"> 
                        <div class="main-left">
                            <table class="tb2">
                                <tr>
                                    <td>TO </td> <td style="width: 50px;text-align: center">:</td>
                                    <td style="max-width: 405px"><?= $appointment->getDebtorName($princip); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="main-right">
                            <table class="tb2">
                                <tr>
                                    <td>Date </td> <td style="width: 50px;text-align: center">:</td>
                                    <td style="max-width: 200px"><?= date("d-M-Y h:m") ?></td>
                                </tr>
                                <tr>
                                    <td>Client Code </td> <td style="width: 50px;text-align: center">:</td>
                                    <td style="max-width: 200px"><?= $appointment->getClintCode($appointment->principal); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br/>
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
                                    <td><?= Yii::$app->SetValues->DateFormate($appointment->eta); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="topcontent-center">
                            <table class="">
                                <tr>
                                    <td>Vessel </td> <td>:</td>
                                    <td><?php
                                        if ($appointment->vessel_type == 1) {
                                                echo 'T - ' . Vessel::findOne($appointment->tug)->vessel_name . ' / B - ' . Vessel::findOne($appointment->barge)->vessel_name;
                                        } else {
                                                echo Vessel::findOne($appointment->vessel)->vessel_name;
                                        }
                                        ?>

                                    </td>
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
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Ops no </td> <td>:</td>
                                    <td><?= $appointment->appointment_no ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
                        $grandtotal = 0;
                        $service_categories = ServiceCategorys::find()->orderBy(['(sort_order)' => SORT_ASC])->all();
                        foreach ($service_categories as $service_category) {
                                $subtotal = 0;
                                $estimates = EstimatedProforma::findAll(['apponitment_id' => $appointment->id, 'principal' => $princip, 'service_category' => $service_category->id]);
                                if (!empty($estimates)) {
                                        ?>
                                        <h6><?= $service_category->category_name ?></h6>
                                        <?php
                                        foreach ($estimates as $estimate) {
                                                $subcategories = SubServices::findAll(['estid' => $estimate->id]);
                                                ?>
                                                <table class="table">
                                                    <?php
                                                    if (!empty($subcategories)) {
                                                            ?>

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
                                                    } else {
                                                            ?>

                                                            <tr>
                                                                <td style="width: 30%;"><?= $estimate->service->service ?></td>
                                                                <td style="width: 30%;"><?= $estimate->rate_to_category ?></td>
                                                                <td style="width: 8%;"><?= $estimate->unit ?></td>
                                                                <td style="width: 16%;"><?= $estimate->comments ?></td>
                                                                <td style="width: 8%;"><?= $estimate->unit_rate ?></td>
                                                                <td style="width: 8%;font-weight: bold;"><?= $estimate->epda ?></td>
                                                                <?php
                                                                $subtotal += $estimate->epda;
                                                                ?>
                                                            </tr>
                                                            <?php
                                                    }
                                            }
                                            ?>
                                            <tr>
                                                <td colspan="5" style="text-align: center;font-weight: bold;">Sub total:</td>
                                                <td style="font-weight: bold;">AED <?= $subtotal ?></td>
                                            </tr>
                                        </table> 
                                        <?php
                                }
                                $grandtotal+=$subtotal;
                        }
                        ?>
                    </div>
                    <br/>
                    <div class="grandtotal">
                        <table class="table">
                            <tr>
                                <?php
                                $usd = $grandtotal / $appointment->USD;
                                ?>
                                <td style="width: 84%; text-align: center;"><b>Grand Total Estimate</b></td>
                                <td style="width: 8%;font-weight: bold;">USD <?= round($usd, 3); ?></td>
                                <?php
                                $s = explode('.', $grandtotal);
                                $amount = $s[0];
                                $decimal = $s[1];
                                $amount = moneyFormatIndia($amount);

                                //echo $amount;

                                function moneyFormatIndia($num) {
                                        $explrestunits = "";
                                        if (strlen($num) > 3) {
                                                $lastthree = substr($num, strlen($num) - 3, strlen($num));
                                                $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
                                                $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
                                                $expunit = str_split($restunits, 2);
                                                for ($i = 0; $i < sizeof($expunit); $i++) {
                                                        // creates each of the 2's group and adds a comma to the end
                                                        if ($i == 0) {
                                                                $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
                                                        } else {
                                                                $explrestunits .= $expunit[$i] . ",";
                                                        }
                                                }
                                                $thecash = $explrestunits . $lastthree;
                                        } else {
                                                $thecash = $num;
                                        }
                                        return $thecash; // writes the final format where $currency is the currency symbol.
                                }

                                if ($decimal != 0) {
                                        $amount = $amount . '.' . $decimal . '/-';
                                } else {
                                        $amount = $amount . '/-';
                                }
                                //setlocale(LC_MONETARY, 'en_us');
                                //$gt = money_format('%i', $grandtotal);
                                ?>
                                <td style="width: 8%;font-weight: bold;">AED <?= $amount ?></td>
                            </tr>
                        </table>
                        <button style="float:right;background-color: yellow;padding-left: 20px;padding-right: 20px;">E & OE</button>
                    </div>
                    <br/>
                    <div class="content">
                        <p class="para-heading" style="font-size: 10px;">- Additional scope of work other than mentioned in the tarrif to be mutually agreed between two parties prior initiation of service.</p>
                        <p class="para-content">
                            Please note that this is a pro-forma disbursement account only. It is intended to be an estimate of the actual disbursement account and is for guidance purposes only. 
                            Whilst Emperor Shipping Lines does take every care to ensure that the figures and information contained in the pro-forma disbursement account are as accurate as possibles
                            ,the actual disbursement account may, and often does, for various reasons beyond our control, vary from the pro-forma disbursement account. 
                        </p>

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
                </td>
            </tr>
            <tr>
                <td>
                    <div class="bankdetails">
                        <h3>BANK DETAILS: </h3>
                        <table class="table">
                            <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;">CURRENCIES ACCEPTED : USD / AED / EURO / GBP</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">NAME</td>
                                <td>EMPEROR SHIPPING LINES LLC</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">A/C NO</td>
                                <td>90050200004102</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">IBAN</td>
                                <td>AE150110090050200004102</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">BANK NAME</td>
                                <td>Bank of Baroda</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">SWIFT</td>
                                <td>BARBAEADRAK</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">BRANCH</td>
                                <td>RAS AL KHAIMAH, UAE</td>
                            </tr>
                            <tr>
                                <td style="width:30%;text-align: left;">Correspondent Bank in USA</td>
                                <td>Bank of Baroda,Newyork <br/>Swift Code : BARBUS33</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="bankdetails">
                        <div class="bankdetails-left">
                            <h5>Account Manager</h5> 
                            <a href="#" style="color: #03a9f4;">accrak@emperor.ae</a>
                            <h5>T: +971 7 268 9676(Ext: 205)</h5>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="address">
                        <h3>ADDRESS: </h3>
                        <table class="table">
                            <tr>
                                <td colspan="2" style="text-align: center;font-weight: bold;text-decoration: underline;">GENERAL ADDRESS:</td>
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
                                <td colspan="2"><h4 style="text-align: center;font-weight: bold;text-decoration: underline;">CONTACT DETAILS:</h4>
                                    <p>TEL: +971 7 268 9676 (24x7) <br/> FAX: +971 7 268 9677 <br/> COMMON EMAIL:<a href="#" style="color: #03a9f4;">OPSRAK@EMPEROR.AE</a></p>
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
<div style="display:inline-block">
    <div class="print" style="float:left;">
        <button onclick="printContent('print')" style="font-weight: bold !important;">Print</button>
        <button onclick="window.close();" style="font-weight: bold !important;">Close</button>

    </div>
    <div class="save" style="float:left;">
        <?php
        echo Html::a('<span>SAVE</span>', ['/appointment/estimated-proforma/save-report', 'id' => $appointment->id], ['class' => 'btn btn-gray']);
        ?> 
    </div>
</div>
<!--</body>
</html>-->
