<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\InvoiceType;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceCategorys */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-categorys-form form-inline">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_type')->dropDownList(ArrayHelper::map(InvoiceType::findAll(['status' => 1]), 'id', 'invoice_type'), ['prompt' => '-Choose a Invoice Type-']) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
