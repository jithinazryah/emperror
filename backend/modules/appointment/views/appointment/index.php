<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\VesselType;
use common\models\Vessel;
use common\models\Ports;
use common\models\Terminal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppointmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appointments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appointment-index">

        <div class="row">
                <div class="col-md-12">

                        <div class="panel panel-default">
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
                                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                                        <?= Html::a('<i class="fa-th-list"></i><span> Create Appointment</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                        <?=
                                        GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
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
                                                                    return 'T -'.Vessel::findOne($data->tug)->vessel_name . ' / B -' . Vessel::findOne($data->barge)->vessel_name;
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
                                                [
                                                    'attribute' => 'terminal',
                                                    'value' => function($data) {
                                                            return Terminal::findOne($data->terminal)->terminal;
                                                    },
                                                    'filter' => ArrayHelper::map(Terminal::find()->asArray()->all(), 'id', 'terminal'),
                                                ],
                                                // 'birth_no',
                                                'appointment_no',
                                                // 'no_of_principal',
                                                // 'principal',
                                                // 'nominator',
                                                // 'charterer',
                                                // 'shipper',
                                                // 'purpose',
                                                // 'cargo',
                                                // 'quantity',
                                                // 'last_port',
                                                // 'next_port',
                                                // 'eta',
                                                // 'stage',
                                                [
                                                    'attribute' => 'status',
                                                    'format' => 'raw',
                                                    'filter' => [1 => 'Enabled', 0 => 'disabled'],
                                                    'value' => function ($model) {
                                                    return $model->status == 1 ? 'Enabled' : 'disabled';
                                            },
                                                ],
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


