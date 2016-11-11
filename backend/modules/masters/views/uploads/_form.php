<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Uploads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uploads-form form-inline">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'form_name')->textInput(['maxlength' => true]) ?>

    <?php if ($model->isNewRecord) { ?>

            <?= $form->field($model, 'upload_file')->fileInput() ?>
    <?php } else {
            ?>
            <div class="form-group field-uploads-upload_file has-success">
                <label class="control-label" for="uploads-upload_file">Upload File</label>
                <input type="hidden" name="Uploads[upload_file]" value=""><input type="file" id="uploads-upload_file" name="Uploads[upload_file]">
                <a href="<?= Yii::$app->homeUrl ?>uploads/common_uploads/<?= $model->id ?>/<?= $model->upload_file ?>"><?= $model->upload_file ?></a>
                <div class="help-block"></div>
            </div>
    <?php }
    ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
    <div class="form-group field-uploads-comment has-success"></div>
    <div class="form-group field-uploads-comment has-success"></div>
    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
