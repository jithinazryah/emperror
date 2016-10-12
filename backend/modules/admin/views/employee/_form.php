<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Branch;
use common\models\AdminPosts;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form form-inline">

        <?php $form = ActiveForm::begin(); ?>

        <?php $posts = ArrayHelper::map(AdminPosts::findAll(['status' => 1]), 'id', 'post_name'); ?>
        <?= $form->field($model, 'post_id')->dropDownList($posts, ['prompt' => '-Choose a Post-']) ?>

        <?php $branch = ArrayHelper::map(Branch::findAll(['status' => 1]), 'id', 'branch_name'); ?>
        <?= $form->field($model, 'branch_id')->dropDownList($branch, ['options' => Yii::$app->SetValues->Selected($model->branch_id), 'prompt' => '-Choose a Branch-', 'multiple' => true]) ?>


        <?php if ($model->isNewRecord) { ?>

                <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>
        <?php } ?>

        <?= $form->field($model, 'employee_code')->textInput(['maxlength' => true]) ?>
        <?php if ($model->isNewRecord) { ?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?php } ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'gender')->dropDownList(['1' => 'Male', '0' => 'Female']) ?>

        <?= $form->field($model, 'maritual_status')->dropDownList(['1' => 'Married', '0' => 'Unmarried']) ?>

        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

        <?=
        $form->field($model, 'date_of_join')->widget(\yii\jui\DatePicker::classname(), [
            //'language' => 'ru',
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control']
       ])
        ?>

        <?= $form->field($model, 'salary_package')->textInput() ?>

        <?= $form->field($model, 'photo')->fileInput() ?>

                <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

        <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
        </div>

<?php ActiveForm::end(); ?>

</div>
