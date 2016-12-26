<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\VesselType;
use common\models\Vessel;
use common\models\Ports;
use common\models\Terminal;
use common\models\Debtor;
use common\models\Contacts;
use common\models\Purpose;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Appointment */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
        .new-width{
                width: 17% !important;
        }
        .form-group-new{
                padding-left: 29px;
                width: 83%;
        }
        .form-group-new label{
                color:black;
                font-weight: bold;
        }
        span a{
                font-size: 16px;
                color: #1630bf;
        }
</style>

<div class="appointment-form form-inline">

        <?php $form = ActiveForm::begin(); ?>
        <?php // $form->errorSummary($model)  ?>

        <?= $form->field($model, 'vessel_type')->dropDownList(ArrayHelper::map(VesselType::findAll(['status' => 1]), 'id', 'vessel_type'), ['prompt' => '-Choose a Vessel Type-', 'class' => 'form-control vessels']) ?>

        <?php
//    $flag_vessel = true;
//    $flag_tug = true;
//    $flag_barge = true;
//    if (!$model->isNewRecord) {
//        if ($model->vessel_type != '') {
//            if ($model->vessel_type == 1) {
//                $flag_vessel = true;
//                $flag_tug = false;
//                $flag_barge = false;
//            } else {
//                $flag_vessel = FALSE;
//                $flag_tug = TRUE;
//                $flag_barge = false;
//            }
//        }
//    }
        ?>
        <?= $form->field($model, 'vessel')->dropDownList(ArrayHelper::map(Vessel::findAll(['status' => 1, 'vessel_type' => $model->vessel_type]), 'id', 'vessel_name'), ['prompt' => '-Choose a Vessel-', 'disabled' => $model->vessel_type == 1 ? TRUE : FALSE]) ?>


        <?= $form->field($model, 'tug')->dropDownList(ArrayHelper::map(Vessel::findAll(['status' => 1, 'vessel_type' => 4]), 'id', 'vessel_name'), ['prompt' => '-Choose a Tug-', 'disabled' => $model->vessel_type != 1 ? TRUE : FALSE]) ?>

        <?= $form->field($model, 'barge')->dropDownList(ArrayHelper::map(Vessel::findAll(['status' => 1, 'vessel_type' => 6]), 'id', 'vessel_name'), ['prompt' => '-Choose a Barge-', 'disabled' => $model->vessel_type != 1 ? TRUE : FALSE]) ?>

        <?= $form->field($model, 'port_of_call')->dropDownList(ArrayHelper::map(Ports::findAll(['status' => 1]), 'id', 'port_name'), ['prompt' => '-Choose a Port-', 'class' => 'form-control ports', 'disabled' => $model->isNewRecord ? FALSE : TRUE]) ?>

        <?= $form->field($model, 'terminal')->dropDownList(ArrayHelper::map(Terminal::findAll(['status' => 1]), 'id', 'terminal'), ['prompt' => '-Choose a Terminal-']) ?>

        <?php // $form->field($model, 'terminal')->textInput(['maxlength' => true])    ?>

        <?= $form->field($model, 'birth_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'appointment_no')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?php // $form->field($model, 'no_of_principal')->textInput(['maxlength' => true])   ?>
        <?php
        if ($model->isNewRecord) {
                $model->no_of_principal = 1;
        }
        ?>
        <?php $arr = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'); ?>
        <?= $form->field($model, 'no_of_principal')->dropDownList($arr, ['prompt' => '-choose no of principal-']) ?>

        <?=
        $form->field($model, 'principal', ['template' => "<div class='overly'></div>\n{label}\n{input}\n{hint}\n{error}"]
        )->dropDownList(ArrayHelper::map(Debtor::findAll(['status' => 1]), 'id', 'principal_name'), ['options' => Yii::$app->SetValues->Selected($model->principal), 'prompt' => '-Choose a Principal-', 'multiple' => true])
        ?>

        <?= $form->field($model, 'nominator')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 1])->all(), 'id', 'name'), ['prompt' => '-Choose a Nominator-']) ?>

        <?= $form->field($model, 'charterer')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 2])->all(), 'id', 'name'), ['prompt' => '-Choose a Charterer-']) ?>

        <?= $form->field($model, 'shipper')->dropDownList(ArrayHelper::map(Contacts::find()->where(new Expression('FIND_IN_SET(:contact_type, contact_type)'))->addParams([':contact_type' => 3])->all(), 'id', 'name'), ['prompt' => '-Choose a Shipper-']) ?>

        <?= $form->field($model, 'purpose')->dropDownList(ArrayHelper::map(Purpose::findAll(['status' => 1]), 'id', 'purpose'), ['prompt' => '-Choose a Purpose-']) ?>

        <?= $form->field($model, 'cargo')->textInput(['maxlength' => true, 'disabled' => $model->isNewRecord ? FALSE : $model->status == 0 ? TRUE : FALSE]) ?>

        <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_port')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'next_port')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'eta')->textInput() ?>

        <?= $form->field($model, 'client_reference')->textInput() ?>

        <?php //$form->field($model, 'stage')->textInput()      ?>

        <?= $form->field($model, 'status')->dropDownList(['1' => 'Enabled', '0' => 'Disabled']) ?>

        <?php
        if ($model->isNewRecord) {
                if ($model->epda_content == '') {
                        $content = '- Additional scope of work other than mentioned in the tarrif to be mutually agreed between two parties prior initiation of service.';
                        ?>
                        <?=
                        $form->field($model, 'epda_content', ['options' => ['class' => 'form-group-new']])->textArea([
                            'rows' => '6', 'value' => $content
                        ])
                        ?>
                        <?php
                }
        } else {
                ?>
                <?=
                $form->field($model, 'epda_content', ['options' => ['class' => 'form-group-new']])->textArea([
                    'rows' => '6', 'value' => $content
                ])
                ?>
                <?php // $form->field($model, 'epda_content')->textArea(['rows' => '6', 'template' => '{label} <div class="row">EPDA Content<div class="col-sm-4">{input}{error}{hint}</div></div>']) ?>
                <?php
        }
        ?>

        <?= "<br>" ?>

        <?= $form->field($model, 'final_draft_bl', ['options' => ['class' => 'new-width form-group'], 'template' => '{label}<label></label>{input}{error}'])->fileInput(['maxlength' => true]) ?>
        <div class="form-group "></div>
        <div class="form-group "></div>
        <div class="form-group">
                <input type="checkbox" id="queue-order" name="check" value="1" checked="checked" uncheckValue="0"><label>Load Previous Proforma</label>
        </div>
        <div class="form-group" style="float: right;">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top: 18px;']) ?>
        </div>



        <?php ActiveForm::end(); ?>

        <br/>
        <hr class="appoint_history" />
        <div style="text-align: center;">
                <h4 class="sub-heading">Uploaded Files</h4>
                <br/>
                <?php
                $path = Yii::$app->basePath . '/web/uploads/final_draft' . '/' . $model->id;

                foreach (glob("{$path}/*") as $file) {
                        $arry = explode('/', $file);
                        ?>
                        <span class="upload_file_list"><?= Html::a(end($arry), Yii::$app->homeUrl . 'uploads/final_draft/' . $model->id . '/' . end($arry), ['target' => '_blank']) ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= Html::a('<i class="fa fa-remove"></i>', Yii::$app->homeUrl . 'appointment/appointment/remove?path=uploads/final_draft/' . $model->id . '/' . end($arry)) ?></span>
                        <?php
                }
                ?>
        </div>


</div>
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
<script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>
<script>
        $("document").ready(function () {
                $('.ports').change(function () {
                        var port_id = $(this).val();
                        $.ajax({
                                type: 'POST',
                                cache: false,
                                data: {port_id: port_id},
                                url: '<?= Yii::$app->homeUrl; ?>/appointment/appointment/appointment-no',
                                success: function (data) {
                                        $('#appointment-appointment_no').val(data);
                                }
                        });
                });
        });</script>
<script>
        $("document").ready(function () {
                $('#appointment-vessel_type').change(function () {
                        var vessel_type = $(this).val();
                        if (vessel_type == 1) {
                                $("#appointment-vessel").prop('disabled', true);
                                $("#appointment-tug").prop('disabled', false);
                                $("#appointment-barge").prop('disabled', false);
                        }
//                        else if (vessel_type == 2) {
//                                $("#appointment-vessel").prop('disabled', true);
//                                $("#appointment-tug").prop('disabled', false);
//                                $("#appointment-barge").prop('disabled', true);
//                        }
//                        else if (vessel_type == 3) {
//                                $("#appointment-vessel").prop('disabled', true);
//                                $("#appointment-tug").prop('disabled', true);
//                                $("#appointment-barge").prop('disabled', false);
//                        }
                        else {
                                $.ajax({
                                        type: 'POST',
                                        cache: false,
                                        data: {vessel_type: vessel_type},
                                        url: '<?= Yii::$app->homeUrl; ?>/appointment/appointment/vessel-type',
                                        success: function (data) {
                                                if (data != 'Tug &Barge') {

                                                        $("#appointment-tug").prop('disabled', true);
                                                        $("#appointment-barge").prop('disabled', true);
                                                        $("#appointment-vessel").prop('disabled', false);
                                                        var index = $('#appointment-tug').get(0).selectedIndex;
                                                        $('#appointment-tug option:eq(' + index + ')').prop("selected", false);
                                                        var indexs = $('#appointment-barge').get(0).selectedIndex;
                                                        $('#appointment-barge option:eq(' + indexs + ')').prop("selected", false);
                                                        $('#appointment-vessel').html(data);
                                                }

                                        }
                                });
                        }
                });
        });</script>
<script>
        $("document").ready(function () {
                /* $('#appointment-no_of_principal').change(function () {
                 var no_of_principal = $(this).val();
                 $.ajax({
                 type: 'POST',
                 cache: false,
                 data: {no_of_principal: no_of_principal},
                 url: '<?= Yii::$app->homeUrl; ?>/appointment/appointment/principal',
                 success: function (data) {
                 $('#appointment-principal').html(data);
                 }
                 });
                 });*/

                /*$('#appointment-principal').change(function (e) {
                 var principal = $(this).val();
                 var No_principal = $('#appointment-no_of_principal').val();
                 if (principal.length <= No_principal) {
                 return true;
                 } else {
                 var last = principal[principal.length - 1];
                 $("#appointment-principal option[value='" + last + "']").prop("selected", false);
                 // $('#s2id_autogen4').prop('disabled', true);
                 alert("Choose Principal same as Number of principal");
                 return false;
                 }

                 });*/

                $('#appointment-principal').change(function (e) {
                        var principal = $(this).val();
                        var No_principal = $('#appointment-no_of_principal').val();
                        if (principal.length == No_principal) {
                                //alert("Principal same as Number of principal");
                                $('#s2id_autogen4').prop('disabled', true);
                                $('.overly').addClass('over-active');
                        } else if (principal.length < No_principal) {
                                $('#s2id_autogen4').prop('disabled', false);
                                $('.overly').removeClass('over-active');
                        } else if (principal.length > No_principal) {
                                var last = principal[principal.length - 1];
                                $("#appointment-principal option[value='" + last + "']").prop("selected", false);
                                alert("Choose Principal same as Number of principal");
                                $('#s2id_autogen4').prop('disabled', true);
                                $('.overly').addClass('over-active');
                        }

                });
                $('#appointment-no_of_principal').change(function (e) {
                        var principal = $('#appointment-no_of_principal').val();
                        var No_principal = $(this).val();
                        if (principal.length == No_principal) {
                                $('#s2id_autogen4').prop('disabled', true);
                                $('.overly').addClass('over-active');
                        } else if (principal.length < No_principal) {
                                $('#s2id_autogen4').prop('disabled', false);
                                $('.overly').removeClass('over-active');
                        } else if (principal.length > No_principal) {
                                $('#s2id_autogen4').prop('disabled', true);
                                $('.overly').addClass('over-active');
                        }
                });
        });</script>
<script type="text/javascript">
        jQuery(document).ready(function ($)
        {
                $("#appointment-nominator").select2({
                        //placeholder: 'Select your country...',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });
                $("#appointment-charterer").select2({
                        //placeholder: 'Select your country...',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });
                $("#appointment-shipper").select2({
                        //placeholder: 'Select your country...',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });

                $("#appointment-principal").select2({
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
        .txtarea{
                width:81% !important;
                margin-left: 28px;
                height: 150px;
        }
        .appoint_history{
                border-top: 2px solid #eee !important;
        }

</style>


<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/multiselect/css/multi-select.css">
<script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>
<script src="<?= Yii::$app->homeUrl; ?>/js/multiselect/js/jquery.multi-select.js"></script>
<script type="text/javascript">
        jQuery(document).ready(function ($)
        {
                $("#appointment-vessel").select2({
                        //placeholder: 'Select your country...',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });
                $("#appointment-tug").select2({
                        //placeholder: 'Select your country...',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });
                $("#appointment-barge").select2({
                        //placeholder: 'Select your country...',
                        allowClear: true
                }).on('select2-open', function ()
                {
                        // Adding Custom Scrollbar
                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                });

        });</script>


<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2.css">
<link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/js/select2/select2-bootstrap.css">
<script src="<?= Yii::$app->homeUrl; ?>/js/select2/select2.min.js"></script>

