<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PortCallDataRob */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Port Call Data Robs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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
                                <?=  Html::a('<i class="fa-th-list"></i><span> Manage Port Call Data Rob</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                <div class="panel-body"><div class="port-call-data-rob-view">
                                                <p>
                                                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                                                        'class' => 'btn btn-danger',
                                                        'data' => [
                                                        'confirm' => 'Are you sure you want to delete this item?',
                                                        'method' => 'post',
                                                        ],
                                                        ]) ?>
                                                </p>

                                                <?= DetailView::widget([
                                                'model' => $model,
                                                'attributes' => [
                                                            'id',
            'appointment_id',
            'fo_arrival_unit',
            'fo_arrival_quantity',
            'do_arrival_unit',
            'do_arrival_quantity',
            'go_arrival_unit',
            'go_arrival_quantity',
            'lo_arrival_unit',
            'lo_arrival_quantity',
            'fresh_water_arrival_unit',
            'fresh_water_arrival_quantity',
            'fo_sailing_unit',
            'fo_sailing_quantity',
            'do_sailing_unit',
            'do_sailing_quantity',
            'go_sailing_unit',
            'go_sailing_quantity',
            'lo_sailing_unit',
            'lo_sailing_quantity',
            'fresh_water_sailing_unit',
            'fresh_water_sailing_quantity',
            'additional_info',
            'comments:ntext',
            'status',
            'CB',
            'UB',
            'DOC',
            'DOU',
                                                ],
                                                ]) ?>
</div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>


