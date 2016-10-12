<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AdminPosts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-posts-form form-inline">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'post_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'admin')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

        <?= $form->field($model, 'masters')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

        <?= $form->field($model, 'appointments')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

        <?= $form->field($model, 'estimated_proforma')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

        <?= $form->field($model, 'port_call_data')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

        <?= $form->field($model, 'close_estimate')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

        <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>


        <div class="form-group" style="float: right;">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
