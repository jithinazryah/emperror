<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\ServiceCategorys;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Services */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
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
                <?= Html::a('<i class="fa-th-list"></i><span> Manage Services</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                <div class="panel-body"><div class="services-view">
                        <p>
                            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?=
                            Html::a('Delete', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ])
                            ?>
                        </p>

                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'category_id',
                                    'value' => $model->category->category_name,
                                ],
                                'service',
//                                [
//                                    'attribute' => 'invocie_type',
//                                    'value' => $model->invoicetype0->invoice_type,
//                                ],
                                [
                                    'attribute' => 'invocie_type',
                                    'value' => call_user_func(function ($data) {
                                                    $invocie_type = explode(',', $data->invocie_type);
                                                    $result = '';
                                                    foreach ($invocie_type as $invoice) {
                                                            $result .= $data->getInvoiceName($invoice) . ', ';
                                                    }
                                                    return rtrim($result, ",");
                                            }, $model),
                                ],
                                [
                                    'attribute' => 'supplier',
                                    'value' => call_user_func(function ($data) {
                                                    $supplier = explode(',', $data->supplier);
                                                    $result = '';
                                                    foreach ($supplier as $supplie) {
                                                            $result .= $data->getSupplierName($supplie) . ', ';
                                                    }
                                                    return rtrim($result, ",");
                                            }, $model),
                                ],
                                'unit_rate',
                                [
                                    'attribute' => 'unit',
                                    'value' => $model->unit0->unit_name,
                                ],
                                [
                                    'attribute' => 'currency',
                                    'value' => $model->currency0->currency_name,
                                ],
                                'roe',
                                'epda_value',
                                'cost_allocation',
                                'comments:ntext',
                                [
                                    'attribute' => 'status',
                                    'value' => $model->status == 1 ? 'Enabled' : 'Disabled',
                                ],],
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


