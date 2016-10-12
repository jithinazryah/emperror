<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Debtor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="debtor-form form-inline">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'principal_name')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'principal_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tele_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'invoicing_address')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'da_dispatch_addresss_1')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'da_dispatch_addresss_2')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

        <div class="form-group" style="float: right;">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
