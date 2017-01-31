<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GenerateInvoice */

$this->title = 'Update Generate Invoice: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Generate Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
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
                                <?= Html::a('<i class="fa-th-list"></i><span> Manage Invoice</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                <?= Html::a('<i class="fa-th-list"></i><span> Create Invoice</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                <?= Html::a('<i class="fa-th-list"></i><span> Generate Invoice</span>', ['reports', 'id' => $model->id], ['class' => 'btn btn-secondary btn-icon btn-icon-standalone', 'target' => '_blank']) ?>

                                <ul class="estimat nav nav-tabs nav-tabs-justified">
                                        <li class="active">
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Generate Invoice</span>', ['generate-invoice/update', 'id' => $model->id]);
                                                ?>

                                        </li>
                                        <li>
                                                <?php
                                                echo Html::a('<span class="visible-xs"><i class="fa-home"></i></span><span class="hidden-xs">Invoice Description</span>', ['generate-invoice/add', 'id' => $model->id]);
                                                ?>

                                        </li>
                                </ul>
                                <div class="panel-body"><div class="generate-invoice-create">
                                                <?=
                                                $this->render('_form', [
                                                    'model' => $model,
                                                ])
                                                ?>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>
