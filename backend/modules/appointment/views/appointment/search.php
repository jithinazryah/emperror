<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Appointment;
use common\models\Debtor;
use common\models\Vessel;
?>
<!DOCTYPE html>
<html>
        <head>
                <style>
                        .th1{
                                text-align: center;
                        }
                        table.table tr td:last-child {
                                display: block;
                                border: 1px solid #b7b7b7;
                        }
                </style>
        </head>
        <body>
                <table class="table table-bordered">
                        <tr>
                                <th class="th1">Appointment ID</th>
                                <th class="th1">Appointment Number</th>
                                <th class="th1">Vessel Name</th>
                                <th class="th1">Imo No</th>
                        </tr>
                        <?php
                        foreach ($appointment as $value) {
                                ?>
                                <tr>
                                        <td><?= $value->id ?></td>
                                        <td><?= $value->appointment_no ?></td>
                                        <?php
                                        if ($value->vessel_type != 1) {
                                                ?>
                                                <td><?= Vessel::findOne($value->vessel)->vessel_name; ?></td>
                                                <td><?= Vessel::findOne($value->vessel)->imo_no; ?></td>
                                                <?php
                                        } else {
                                                ?>
                                                <td><?php echo 'T - ' . Vessel::findOne($value->tug)->vessel_name . ' / B - ' . Vessel::findOne($value->barge)->vessel_name; ?></td>
                                                <td><?php echo Vessel::findOne($value->tug)->imo_no . ' , ' . Vessel::findOne($value->barge)->imo_no; ?></td>
                                                <?php
                                        }
                                        ?>
                                </tr>
                                <?php
                        }
                        ?>

                </table>
        </body>
</html>

