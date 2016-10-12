<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Branch;
use common\models\AdminPosts;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

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
                                        <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

                                        <?= Html::a('<i class="fa-th-list"></i><span> Create Employee</span>', ['create'], ['class' => 'btn btn-warning  btn-icon btn-icon-standalone']) ?>
                                        <?php Pjax::begin(); ?>                                                                                                        <?=
                                        GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                // 'id',
                                                [
                                                    'attribute' => 'photo',
                                                    'format'=>'raw',      
                                                    'value' => function ($data) {
                                                                $img = '<img width="120px" src="'.Yii::$app->homeUrl.'uploads/'.$data->photo.'"/>';
                                                            return $img;
                                                    },
                                                ],
                                                [
                                                    'attribute' => 'post_id',
                                                    'value' => 'post.post_name',
                                                    'filter' => ArrayHelper::map(AdminPosts::find()->asArray()->all(), 'id', 'post_name'),
                                                ],
                                                [
                                                    'attribute' => 'branch_id',
                                                    'value' => function ($data) {
                                                            $branch = explode(',', $data->branch_id);
                                                            $result = '';
                                                            foreach ($branch as $brnch) {
                                                                    $result .= $data->getBranchName($brnch) . ', ';
                                                            }
                                                            return rtrim($result, ",");
                                                    },
                                                ],
                                                // 'branch_id',
                                                'user_name',
                                                'employee_code',
                                                // 'password',
                                                // 'name',
                                                 'email:email',
                                                // 'phone',
                                                // 'gender',
                                                // 'maritual_status',
                                                // 'address:ntext',
                                                // 'date_of_join',
                                                // 'salary_package',
                                                // 'photo',
                                                // 'status',
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


