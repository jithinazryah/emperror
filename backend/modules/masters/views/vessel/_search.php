<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VesselSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vessel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'vessel_type') ?>

    <?= $form->field($model, 'vessel_name') ?>

    <?= $form->field($model, 'imo_no') ?>

    <?= $form->field($model, 'official') ?>

    <?php // echo $form->field($model, 'mmsi_no') ?>

    <?php // echo $form->field($model, 'owners_info') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'land_line') ?>

    <?php // echo $form->field($model, 'direct_line') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'picture') ?>

    <?php // echo $form->field($model, 'dwt') ?>

    <?php // echo $form->field($model, 'grt') ?>

    <?php // echo $form->field($model, 'nrt') ?>

    <?php // echo $form->field($model, 'loa') ?>

    <?php // echo $form->field($model, 'beam') ?>

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
