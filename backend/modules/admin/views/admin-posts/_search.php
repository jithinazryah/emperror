<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AdminPostsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-posts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'post_name') ?>

    <?= $form->field($model, 'admin') ?>

    <?= $form->field($model, 'masters') ?>

    <?= $form->field($model, 'appointments') ?>

    <?php // echo $form->field($model, 'estimated_proforma') ?>

    <?php // echo $form->field($model, 'port_call_data') ?>

    <?php // echo $form->field($model, 'close_estimate') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'CB') ?>

    <?php // echo $form->field($model, 'UB') ?>

    <?php // echo $form->field($model, 'DOC') ?>

    <?php // echo $form->field($model, 'DOU') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
