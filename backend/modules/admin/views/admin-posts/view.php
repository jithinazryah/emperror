<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AdminPosts */

$this->title = $model->post_name;
$this->params['breadcrumbs'][] = ['label' => 'Admin Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-posts-view">

        <div class="row">
                <div class="col-md-12">

                        <div class="panel panel-default">
                                <div class="panel-heading">
                                        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>

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
                                        <?= Html::a('<i class="fa-th-list"></i><span>Manage Admin Posts</span>', ['index'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                        <div class="panel-body"><div class="admin-posts-view">
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
                                                               // 'id',
                                                                'post_name',
                                                                [
                                                                    'label' => 'Admin',
                                                                    'format' => 'raw',
                                                                    'value' => $model->admin == 1 ? 'Yes' : 'No',
                                                                ],
                                                                [
                                                                    'label' => 'Masters',
                                                                    'format' => 'raw',
                                                                    'value' => $model->masters == 1 ? 'Yes' : 'No',
                                                                ],
                                                                [
                                                                    'label' => 'Appointments',
                                                                    'format' => 'raw',
                                                                    'value' => $model->appointments == 1 ? 'Yes' : 'No',
                                                                ],
                                                                [
                                                                    'label' => 'Estimated proforma',
                                                                    'format' => 'raw',
                                                                    'value' => $model->estimated_proforma == 1 ? 'Yes' : 'No',
                                                                ],
                                                                [
                                                                    'label' => 'Port call data',
                                                                    'format' => 'raw',
                                                                    'value' => $model->port_call_data == 1 ? 'Yes' : 'No',
                                                                ],
                                                                [
                                                                    'label' => 'Close estimate',
                                                                    'format' => 'raw',
                                                                    'value' => $model->close_estimate == 1 ? 'Yes' : 'No',
                                                                ],
                                                                [
                                                                    'label' => 'Status',
                                                                    'format' => 'raw',
                                                                    'value' => $model->status == 1 ? 'Enabled' : 'disabled',
                                                                ],
                                                            ],
                                                        ])
                                                        ?>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>

</div>