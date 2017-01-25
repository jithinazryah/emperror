<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DebtorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Debtors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="debtor-index">

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

                                        <?= Html::a('<i class="fa-th-list"></i><span> Create Debtor</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                        <?php Pjax::begin(); ?>                                                                                                        <?=
                                        GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                    ['class' => 'yii\grid\SerialColumn'],
                                                'principal_name',
                                                'principal_id',
//                                                'principal_ref_no',
                                                'address:ntext',
//                                                'mobile',
//                                                'tele_phone',
//                                                'fax',
//                                                [
//                                                    'attribute' => 'status',
//                                                    'format' => 'raw',
//                                                    'filter' => [1 => 'Enabled', 0 => 'disabled'],
//                                                    'value' => function ($model) {
//                                                    return $model->status == 1 ? 'Enabled' : 'disabled';
//                                            },
//                                                ],
//                                                ['class' => 'yii\grid\ActionColumn'],
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'headerOptions' => ['style' => 'width: 70px;'],
                                                    'template' => '{view} {update} {delete} {onaccount}',
                                                    'buttons' => [
                                                        'view' => function ($url) {
                                                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'View')]);
                                                        },
                                                        'update' => function ($url) {
                                                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update')]);
                                                        },
                                                        'delete' => function ($url) {
                                                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => Yii::t('app', 'Delete')]);
                                                        },
                                                        'onaccount' => function ($url) {
                                                                return Html::a('<span><i class="fa fa-credit-card" aria-hidden="true"></i></span>', $url, ['title' => Yii::t('app', 'On Account')]);
                                                        }
                                                    ],
                                                    'urlCreator' => function ($action, $model, $key, $index) {
                                                            if ($action === 'view') {
                                                                    $url = \yii\helpers\Url::toRoute(['/masters/debtor/view', 'id' => $key]);
                                                                    return $url;
                                                            } elseif ($action === 'update') {
                                                                    $url = \yii\helpers\Url::toRoute(['/masters/debtor/update', 'id' => $key]);
                                                                    return $url;
                                                            } elseif ($action === 'delete') {
                                                                    $url = \yii\helpers\Url::toRoute(['/masters/debtor/delete', 'id' => $key]);
                                                                    return $url;
                                                            } elseif ($action === 'onaccount') {
                                                                    $url = \yii\helpers\Url::toRoute(['/masters/on-account/add', 'id' => $key]);
                                                                    return $url;
                                                            }
                                                    }
                                                ],
                                            ],
                                        ]);
                                        ?>
                                        <?php Pjax::end(); ?>                                </div>
                        </div>
                </div>
        </div>
</div>


