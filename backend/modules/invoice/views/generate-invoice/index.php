<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GenerateInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generate-invoice-index">

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

                                        <?= Html::a('<i class="fa-th-list"></i><span> Create Generate Invoice</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                        <?=
                                        GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                    ['class' => 'yii\grid\SerialColumn'],
//                                                'id',
//                                                'invoice',
                                                [
                                                    'attribute' => 'to_address',
                                                    'value' => function($data) {
                                                            return substr($data->to_address, 0, 15) . '...';
                                                    },
                                                ],
//                                                'to_address:ntext',
                                                'invoice_number',
                                                'date',
                                                // 'oops_id',
                                                // 'on_account_of',
                                                // 'job',
                                                // 'payment_terms',
                                                // 'doc_no',
                                                // 'status',
                                                // 'CB',
                                                // 'UB',
                                                // 'DOC',
                                                // 'DOU',
//                                                ['class' => 'yii\grid\ActionColumn', 'template' => '{update}{delete}',],
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'contentOptions' => [],
                                                    'header' => 'Actions',
                                                    'template' => '{update}{print}',
                                                    'buttons' => [
                                                        //view button
                                                        'print' => function ($url, $model) {
                                                                return Html::a('<i class="fa fa-print" aria-hidden="true"></i>', $url, [
                                                                            'title' => Yii::t('app', 'print'),
                                                                            'class' => 'actions',
                                                                            'target' => 'print_popup',
                                                                            'onClick' => "window.open('about:blank','print_popup','width=1200,height=740');"
                                                                ]);
                                                        },
                                                    ],
                                                    'urlCreator' => function ($action, $model) {
                                                            if ($action === 'update') {
                                                                    $url = 'update?id=' . $model->id;
                                                                    return $url;
                                                            }
                                                            if ($action === 'print') {
                                                                    $url = 'reports?id=' . $model->id;
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


