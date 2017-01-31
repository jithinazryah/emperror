<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-order-form form-inline">

        <?php $form = ActiveForm::begin(); ?>
        <?php // $form->errorSummary($model); ?>

        <?= $form->field($model, 'to')->textarea(['rows' => 6]) ?>

        <?php // $form->field($model, 'ref_no')->textInput(['maxlength' => true]) ?>

        <?php // $form->field($model, 'date')->textInput() ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'po_box')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'arrived_from')->textInput(['maxlength' => true]) ?>

        <?=
        $form->field($model, 'arrived_on')->widget(\yii\jui\DatePicker::classname(), [
            //'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
            'value' => date('Y-m-d'),
            'options' => ['class' => 'form-control']
        ])
        ?>

        <?php // $form->field($model, 'arrived_on')->textInput() ?>

        <?= $form->field($model, 'vessel_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'voyage_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

        <div class="form-group"></div>

        <div class="form-group" style="float: right;">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
