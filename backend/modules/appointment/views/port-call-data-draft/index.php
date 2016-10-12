<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PortCallDataDraftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Port Call Data Drafts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="port-call-data-draft-index">

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

                    <?= Html::a('<i class="fa-th-list"></i><span> Create Port Call Data Draft</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                    <?php Pjax::begin(); ?>                                                                                                        <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            //  'id',
                            'appointment_id',
                            'data_id',
                            'intial_survey_commenced',
                            'intial_survey_completed',
                            // 'finial_survey_commenced',
                            // 'finial_survey_completed',
                            // 'fwd_arrival_unit',
                            // 'fwd_arrival_quantity',
                            // 'aft_arrival_unit',
                            // 'aft_arrival_quantity',
                            // 'mean_arrival_unit',
                            // 'mean_arrival_quantity',
                            // 'fwd_sailing_unit',
                            // 'fwd_sailing_quantity',
                            // 'aft_sailing_unit',
                            // 'aft_sailing_quantity',
                            // 'mean_sailing_unit',
                            // 'mean_sailing_quantity',
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


