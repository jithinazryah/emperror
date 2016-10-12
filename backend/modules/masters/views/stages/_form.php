<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\StageCategorys;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Stages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stages-form form-inline">

    <?php $form = ActiveForm::begin(); ?>
<?php
    $dataList=ArrayHelper::map(StageCategorys::find()->asArray()->all(), 'id', 'category_name');
    ?>
    <?= $form->field($model, 'category_id')->dropDownList($dataList, 
         ['prompt'=>'-Choose a Category-']) ?>

    <?= $form->field($model, 'stage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
