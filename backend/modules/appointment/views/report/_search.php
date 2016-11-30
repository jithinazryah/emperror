<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\VesselType;
use common\models\Vessel;
use common\models\Ports;
use common\models\Terminal;
use common\models\Debtor;
use common\models\Contacts;
use common\models\Purpose;
use yii\db\Expression;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="reportsearch-form form-inline hidediv1">
        <?php
        $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
        ]);
        ?>
        <div class="row">
                <div class="col-md-12">

                        <?= $form->field($model, 'vessel_type')->dropDownList(ArrayHelper::map(VesselType::findAll(['status' => 1]), 'id', 'vessel_type'), ['prompt' => '-Choose a Vessel Type-']) ?>

                        <?= $form->field($model, 'vessel')->dropDownList(ArrayHelper::map(Vessel::findAll(['status' => 1, 'vessel_type' => $model->vessel_type]), 'id', 'vessel_name'), ['prompt' => '-Choose a Vessel-', 'disabled' => $model->vessel_type == 1 ? TRUE : FALSE]) ?>

                        <?= $form->field($model, 'tug')->dropDownList(ArrayHelper::map(Vessel::findAll(['status' => 1, 'vessel_type' => 4]), 'id', 'vessel_name'), ['prompt' => '-Choose a Tug-', 'disabled' => $model->vessel_type != 1 ? TRUE : FALSE]) ?>

                        <?= $form->field($model, 'barge')->dropDownList(ArrayHelper::map(Vessel::findAll(['status' => 1, 'vessel_type' => 6]), 'id', 'vessel_name'), ['prompt' => '-Choose a Barge-', 'disabled' => $model->vessel_type != 1 ? TRUE : FALSE]) ?>

                        <?= $form->field($model, 'port_of_call')->dropDownList(ArrayHelper::map(Ports::findAll(['status' => 1]), 'id', 'port_name'), ['prompt' => '-Choose a Port-']) ?>

                        <?= $form->field($model, 'appointment_no')->textInput() ?>

                        <?= $form->field($model, 'principal')->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1]), 'id', 'principal_name'), ['prompt' => '-Choose a Principal-']) ?>

                        <?= $form->field($model, 'purpose')->dropDownList(ArrayHelper::map(Purpose::findAll(['status' => 1]), 'id', 'purpose'), ['prompt' => '-Choose a Purpose-']) ?>

                        <?= $form->field($model, 'terminal')->dropDownList(ArrayHelper::map(Terminal::findAll(['status' => 1]), 'id', 'terminal'), ['prompt' => '-Choose a Terminal-']) ?>

                        <?=
                        $form->field($model, 'createdFrom')->widget(\yii\jui\DatePicker::classname(), [
                            //'language' => 'ru',
                            'dateFormat' => 'yyyy-MM-dd',
                            'options' => ['class' => 'form-control']
                        ])
                        ?>

                        <?=
                        $form->field($model, 'createdTo')->widget(\yii\jui\DatePicker::classname(), [
                            //'language' => 'ru',
                            'dateFormat' => 'yyyy-MM-dd',
                            'options' => ['class' => 'form-control']
                        ])
                        ?>

                        <?= $form->field($model, 'birth_no')->textInput() ?>

                        <?= $form->field($model, 'nominator')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 1])->all(), 'id', 'name'), ['prompt' => '-Choose a Nominator-']) ?>

                        <?= $form->field($model, 'charterer')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 2])->all(), 'id', 'name'), ['prompt' => '-Choose a Charterer-']) ?>

                        <?= $form->field($model, 'shipper')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 3])->all(), 'id', 'name'), ['prompt' => '-Choose a Shipper-']) ?>

                        <?= $form->field($model, 'cargo')->textInput() ?>

                        <?= $form->field($model, 'last_port')->textInput() ?>

                        <?= $form->field($model, 'next_port')->textInput() ?>

                        <?=
                        $form->field($model, 'etaFrom')->widget(\yii\jui\DatePicker::classname(), [
                            //'language' => 'ru',
                            'dateFormat' => 'yyyy-MM-dd',
                            'options' => ['class' => 'form-control']
                        ])
                        ?>

                        <?=
                        $form->field($model, 'etaTo')->widget(\yii\jui\DatePicker::classname(), [
                            //'language' => 'ru',
                            'dateFormat' => 'yyyy-MM-dd',
                            'options' => ['class' => 'form-control']
                        ])
                        ?>

                        <div class="form-group"></div>

                        <div class="form-group">
                                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                                <?php // Html::submitButton('Reset', ['class' => 'btn btn-default'])  ?>
                        </div>
                </div>
        </div>

        <?php ActiveForm::end(); ?>
</div>
<style>
        .form-inline .form-group {
                width: 11%;
                /* margin-left: 30px; */
                margin-bottom: 22px;
                color: #000000;
        }

</style>
<script>
        $("document").ready(function () {
                $('#reportsearch-vessel_type').change(function () {
                        var vessel_type = $(this).val();
                        if (vessel_type == 1) {
                                $("#reportsearch-vessel").prop('disabled', true);
                                $("#reportsearch-tug").prop('disabled', false);
                                $("#reportsearch-barge").prop('disabled', false);
                        } else {
                                $.ajax({
                                        type: 'POST',
                                        cache: false,
                                        data: {vessel_type: vessel_type},
                                        url: '<?= Yii::$app->homeUrl; ?>/appointment/report/vessel-type',
                                        success: function (data) {
                                                if (data != 'Tug &Barge') {

                                                        $("#reportsearch-tug").prop('disabled', true);
                                                        $("#reportsearch-barge").prop('disabled', true);
                                                        $("#reportsearch-vessel").prop('disabled', false);
                                                        var index = $('#reportsearch-tug').get(0).selectedIndex;
                                                        $('#reportsearch-tug option:eq(' + index + ')').prop("selected", false);
                                                        var indexs = $('#reportsearch-barge').get(0).selectedIndex;
                                                        $('#reportsearch-barge option:eq(' + indexs + ')').prop("selected", false);
                                                        $('#reportsearch-vessel').html(data);
                                                }

                                        }
                                });
                        }
                });
        });
</script>
