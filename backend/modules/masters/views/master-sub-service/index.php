<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Services;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MasterSubServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Sub Services';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-sub-service-index">

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

                    <?= Html::a('<i class="fa-th-list"></i><span> Create Master Sub Service</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            //  'id',
                            [
                                'attribute' => 'service_id',
                                'value' => function($data) {
                                        return Services::findOne($data->service0)->service;
                                },
                                'filter' => ArrayHelper::map(Services::find()->asArray()->all(), 'id', 'service'),
                            ],
                           // 'service_id',
                            'sub_service',
                            'rate_to_category',
                            'unit',
                            'unit_price',
                            'total',
                            'comments:ntext',
                            'status',
                            // 'CB',
                            // 'UB',
                            // 'DOC',
                            // 'DOU',
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


