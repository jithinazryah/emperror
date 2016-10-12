<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ImigrationClearanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imigration Clearances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imigration-clearance-index">

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
                                        
                                        <?=  Html::a('<i class="fa-th-list"></i><span> Create Imigration Clearance</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                                                                                                                                        <?= GridView::widget([
                                                'dataProvider' => $dataProvider,
                                                'filterModel' => $searchModel,
        'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],

                                                            'id',
            'appointment_id',
            'arrived_ps',
            'pob_inbound',
            'first_line_ashore',
            // 'all_fast',
            // 'agent_on_board',
            // 'imi_clearence_commenced',
            // 'imi_clearence_completed',
            // 'pob_outbound',
            // 'cast_off',
            // 'last_line_away',
            // 'cleared_break_water',
            // 'drop_anchor',
            // 'heave_up_anchor',
            // 'pilot_boarded',
            // 'status',
            // 'CB',
            // 'UB',
            // 'DOC',
            // 'DOU',

                                                ['class' => 'yii\grid\ActionColumn'],
                                                ],
                                                ]); ?>
                                                                                                                </div>
                        </div>
                </div>
        </div>
</div>


