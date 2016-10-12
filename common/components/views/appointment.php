<?php

use yii\helpers\Html;
use common\models\Vessel;
?>
<style>
    .appoint{
        width: 100%;
    }
    .appoint .value{
        font-weight: bold;
        text-align: left;
    }
    .appoint .labell{
        text-align: right;
    }
    .appoint .colen{

    }
    .appoint td{
        padding: 10px;
    }
</style>
<table class="appoint">
    <tr>
        <td class="labell">VESSEL-TYPE </td><td class="colen">:</td><td class="value"><?= $appointment->vesselType->vessel_type; ?> </td>
        <td class="labell">VESSEL-NAME </td><td class="colen">:</td><td class="value">
            <?php
            if ($appointment->vessel_type == 1) {
                    echo Vessel::findOne($appointment->tug)->vessel_name . ',' . Vessel::findOne($appointment->barge)->vessel_name;
            } else {
                    echo Vessel::findOne($appointment->vessel)->vessel_name;
            }
            ?>    

        </td>
        <!--<td class="labell">LAST-PORT </td><td class="colen">:</td><td class="value">Tug & Barge </td>-->
        <td class="labell">PORT OF CALL </td><td class="colen">:</td><td class="value"><?= $appointment->portOfCall->port_name; ?> </td>
    </tr>
    <tr>
        <td class="labell">PURPOSE </td><td class="colen">:</td><td class="value"><?= $appointment->purpose0->purpose; ?> </td>
        <td class="labell">CARGO </td><td class="colen">:</td><td class="value"><?= $appointment->cargo; ?> </td>
        <td class="labell">QUANTITY </td><td class="colen">:</td><td class="value"> <?= $appointment->quantity; ?> </td>
    </tr>
    <tr>
        <td class="labell">BERTH NO </td><td class="colen">:</td><td class="value"><?= $appointment->birth_no; ?> </td>
        <td class="labell">LAST PORT </td><td class="colen">:</td><td class="value"><?= $appointment->last_port; ?></td>
        <td class="labell">NEXT-PORT </td><td class="colen">:</td><td class="value"><?= $appointment->next_port; ?> </td>

    </tr>

</table>

