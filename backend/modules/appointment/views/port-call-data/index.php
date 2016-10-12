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

$this->title = 'Port Call Data';
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

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'appointment_no',
                            [
                                'attribute' => 'vessel_type',
                                'value' => 'vesselType.vessel_type',
                                 'filter' => ArrayHelper::map(VesselType::find()->asArray()->all(), 'id', 'vessel_type'),
                            ],
                            [
                                'attribute' => 'vessel',
                                'value' => 'vessel0.vessel_name',
                                 'filter' => ArrayHelper::map(Vessel::find()->asArray()->all(), 'id', 'vessel_name'),
                            ],
                            [
                                'attribute' => 'port_of_call',
                                'value' => 'portOfCall.port_name',
                                 'filter' => ArrayHelper::map(Ports::find()->asArray()->all(), 'id', 'port_name'),
                            ],
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
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url) {
                                            return Html::a(
                                                            '<span class="glyphicon glyphicon-pencil"></span>', $url, [ 'title' => Yii::t('app', 'update')]);
                                    },
                                        ],
                                        'urlCreator' => function ($action, $model, $key, $index) {
                                        if ($action === 'update') {
                                                $url = \yii\helpers\Url::toRoute(['/appointment/port-call-data/update', 'id' => $model->id]);
                                                return $url;
                                        }
                                }
                                    ],
                                ],
                            ]);
                            ?>
                </div>
            </div>
        </div>
    </div>
</div>


