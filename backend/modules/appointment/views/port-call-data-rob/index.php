<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PortCallDataRobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Port Call Data Robs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="port-call-data-rob-index">

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
                    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

                    <?= Html::a('<i class="fa-th-list"></i><span> Create Port Call Data Rob</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                    <?php Pjax::begin(); ?>                                                                                                        <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            //'id',
                            'appointment_id',
                            'fo_arrival_unit',
                            'fo_arrival_quantity',
                            'do_arrival_unit',
                            // 'do_arrival_quantity',
                            // 'go_arrival_unit',
                            // 'go_arrival_quantity',
                            // 'lo_arrival_unit',
                            // 'lo_arrival_quantity',
                            // 'fresh_water_arrival_unit',
                            // 'fresh_water_arrival_quantity',
                            // 'fo_sailing_unit',
                            // 'fo_sailing_quantity',
                            // 'do_sailing_unit',
                            // 'do_sailing_quantity',
                            // 'go_sailing_unit',
                            // 'go_sailing_quantity',
                            // 'lo_sailing_unit',
                            // 'lo_sailing_quantity',
                            // 'fresh_water_sailing_unit',
                            // 'fresh_water_sailing_quantity',
                            // 'additional_info',
                            // 'comments:ntext',
                            'status', [
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
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]);
                    ?>
<?php Pjax::end(); ?>                                </div>
            </div>
        </div>
    </div>
</div>


