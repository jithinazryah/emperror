<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ServiceCategorys;
use common\models\Contacts;
use common\models\Units;
use common\models\Currency;
use common\models\InvoiceType;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Services */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="services-form form-inline">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    echo $form->errorSummary($model);
    $dataList = ArrayHelper::map(ServiceCategorys::find()->asArray()->all(), 'id', 'category_name');
    ?>
    <?= $form->field($model, 'category_id')->dropDownList($dataList, ['prompt' => '-Choose a Category-']) ?>

    <?= $form->field($model, 'service')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invocie_type')->dropDownList(ArrayHelper::map(InvoiceType::findAll(['status' => 1]), 'id', 'invoice_type'), ['prompt' => '-Choose a Invoice Type-', 'multiple' => true]) ?>

    <?= $form->field($model, 'supplier_options')->dropDownList(['1' => 'Yes', '0' => 'No']) ?>

    <?= $form->field($model, 'supplier',['template' => "<div class='overly'></div>\n{label}\n{input}\n{hint}\n{error}"])->dropDownList(ArrayHelper::map(Contacts::findAll(['status' => 1]), 'id', 'name'), ['options' => Yii::$app->SetValues->Selected($model->supplier),'prompt' => '-Choose a Supplier-', 'multiple' => true]) ?>

    <?= $form->field($model, 'unit_rate')->textInput() ?>

    <?= $form->field($model, 'unit')->dropDownList(ArrayHelper::map(Units::findAll(['status' => 1]), 'id', 'unit_name'), ['prompt' => '-Choose a unit-']) ?>

    <?= $form->field($model, 'currency')->dropDownList(ArrayHelper::map(Currency::findAll(['status' => 1]), 'id', 'currency_name'), ['prompt' => '-Choose a Currency-']) ?>

    <?= $form->field($model, 'roe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'epda_value')->textInput() ?>

    <?= $form->field($model, 'cost_allocation')->textInput() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>
    <div class="form-group" style="float: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
        $("document").ready(function () {

            $('#services-supplier_options').change(function (e) {
                var supplier_options = $(this).val();
                if(supplier_options == 1){
                        $("#services-supplier").prop('disabled', false);
                        $('.overly').removeClass('over-active');
                }
                else{
                       $("#services-supplier").prop('disabled', true); 
                       $('.overly').addClass('over-active');
                }
              
            });
            /*
             * Multiple option for supplier
             */
             $("#services-supplier").select2({
                        placeholder: 'Choose Principals',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });

        });
</script>
<style>
        .over-active{
                background-color: rgba(23, 20, 20, 0.11);
                width: 24%;
                height: 9%;
                position: absolute;
                z-index: 100;
        }

</style>

<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/multiselect/css/multi-select.css">
<script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>
<script src="<?= Yii::$app->homeUrl; ?>/js/multiselect/js/jquery.multi-select.js"></script>
