<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Test */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'eta')->textInput() ?>

    <?= $form->field($model, 'ets')->textInput() ?>

    <?= $form->field($model, 'esop')->textInput() ?>

    <?= $form->field($model, 'nor_tenderd')->textInput() ?>

    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
