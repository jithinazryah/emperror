<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\VesselType;
use common\models\Vessel;
use common\models\Ports;
use common\models\Debtor;
use common\models\Terminal;
use common\models\Stages;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppointmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appointments';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
        .advanced-search{
                font-size: 17px;
                text-decoration: underline;
                color: #0023e2;
        }
</style>
<div class="appointment-index">

        <div class="row">
                <div class="col-md-12">

                        <div class="panel panel-default">
                                <a class="advanced-search" style="font-size: 17px;">Advanced Search</a>
                                <br/>
                                <hr class="appoint_history" />
                                <br/>
                                <?= $this->render('_search', ['model' => $searchModel]) ?>
                                <div class="panel-heading">
                                        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>

                                        <div class="panel-options">
                                                <a href="#" data-toggle="panel">
                                                        <span class="collapse-icon">&ndash;</span>
                                                        <span class="expand-icon">+</span>
                                                </a>
                                                <a href="#" data-toggle="remove">
                                                        &times;
                                                </a>
                                        </div>
                                </div>
                                <div class="panel-body">
                                        <?php
//                    echo ListView::widget([
//                        'dataProvider' => $dataProvider,
//                        'itemView' => '_item',
//                    ]);
                                        ?>

                                        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

                                        <?php // Html::a('<i class="fa-th-list"></i><span> Create Appointment</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                        <?=
                                        GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            //'filterModel' => $searchModel,
                                            'columns' => [
                                                    ['class' => 'yii\grid\SerialColumn'],
                                                //  'id',
                                                [
                                                    'attribute' => 'vessel_type',
                                                    'value' => function($data) {
                                                            return VesselType::findOne($data->vessel_type)->vessel_type;
                                                    },
                                                    'filter' => ArrayHelper::map(VesselType::find()->asArray()->all(), 'id', 'vessel_type'),
                                                ],
                                                    [
                                                    'attribute' => 'vessel',
                                                    'value' => function($data) {
                                                            if ($data->vessel_type == 1) {
                                                                    return 'T -' . Vessel::findOne($data->tug)->vessel_name . ' / B -' . Vessel::findOne($data->barge)->vessel_name;
                                                            } else {
                                                                    return Vessel::findOne($data->vessel)->vessel_name;
                                                            }
                                                            // return Vessel::findOne($data->vessel)->vessel_name;
                                                    },
                                                    'filter' => ArrayHelper::map(Vessel::find()->asArray()->all(), 'id', 'vessel_name'),
                                                ],
                                                    [
                                                    'attribute' => 'port_of_call',
                                                    'value' => function($data) {
                                                            return Ports::findOne($data->port_of_call)->port_name;
                                                    },
                                                    'filter' => ArrayHelper::map(Ports::find()->asArray()->all(), 'id', 'port_name'),
                                                ],
                                                'eta',
//                                                [
//                                                    'attribute' => 'terminal',
//                                                    'value' => function($data) {
//                                                            return Terminal::findOne($data->terminal)->terminal;
//                                                    },
//                                                    'filter' => ArrayHelper::map(Terminal::find()->asArray()->all(), 'id', 'terminal'),
//                                                ],
                                                // 'birth_no',
                                                'appointment_no',
                                                // 'no_of_principal',
                                                [
                                                    'attribute' => 'principal',
                                                    'value' => function($data) {
                                                            return Debtor::findOne($data->principal)->principal_id;
                                                    },
                                                    'filter' => ArrayHelper::map(Debtor::find()->asArray()->all(), 'id', 'principal_id'),
                                                ],
//                                                'principal',
                                                // 'nominator',
                                                // 'charterer',
                                                // 'shipper',
                                                // 'purpose',
                                                // 'cargo',
                                                // 'quantity',
                                                // 'last_port',
                                                // 'next_port',
                                                // 'eta',
                                                [
                                                    'attribute' => 'stage',
                                                    'value' => 'stages0.stage',
                                                    'filter' => ArrayHelper::map(Stages::find()->asArray()->all(), 'id', 'stage'),
                                                ],
//                             'stage',
//                            [
//                                'attribute' => 'status',
//                                'format' => 'raw',
//                                'filter' => [1 => 'Enabled', 0 => 'disabled'],
//                                'value' => function ($model) {
//                                return $model->status == 1 ? 'Enabled' : 'disabled';
//                        },
//                            ],
                                                // 'CB',
                                                // 'UB',
                                                // 'DOC',
                                                // 'DOU',
                                                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{update}',],
                                            ],
                                        ]);
                                        ?>
                                </div>
                        </div>
                </div>
        </div>
</div>
<style>
        .hidediv1{
                display:none;
        }
</style>
<script>
        $(document).ready(function () {
                $('.advanced-search').click(function () {
                        $('.hidediv1').slideToggle();
                });
        });
</script>



