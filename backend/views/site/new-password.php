<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<div class="login-container">

        <div class="row">

                <div class="col-sm-6">

                        <script type="text/javascript">
                                jQuery(document).ready(function ($)
                                {
                                        setTimeout(function () {
                                                $(".fade-in-effect").addClass('in');
                                        }, 1);

                                });
                        </script>
                        <!-- Errors container -->
                        <div class="errors-container">
                        </div>

                        <!-- Add class "fade-in-effect" for login form effect -->
                        <?php
                        $form = ActiveForm::begin(
                                        [
                                            'id' => 'forgot-email',
                                            'method' => 'post',
                                            'options' => [
                                                'class' => 'login-form fade-in-effect'
                                            ]
                                        ]
                        );
                        ?>
                        <div style="font-size: 21px;
                             color: hsla(0, 100%, 50%, 0.81);">

                                <?= Yii::$app->session->getFlash('error'); ?>
                                <?= Yii::$app->session->getFlash('success'); ?>
                        </div>
                        <div class="login-header">
                                <!--                                <a href="" class="logo">
                                <?php echo Html::img('@web/images/dr-main-logo.png') ?>
                                                                        <span>Emperor Admin log in</span>
                                                                </a>-->

                                <p>Dear user, Please Enter Your New Password!</p>
                        </div>

                        <div class="form-group">
                                <div class="form-group field-employee-password">
                                        <label class="control-label" for="new-password">new Password</label>
                                        <input type="password" id="new-password" class="form-control" name="new-password" autofocus="false" required>
                                        <p class="help-block help-block-error"></p>
                                </div>

                        </div>
                        <div class="form-group">
                                <div class="form-group field-employee-password">
                                        <label class="control-label" for="confirm-password">Confirm Password</label>
                                        <input type="password" id="confirm-password" class="form-control" name="confirm-password" autofocus="false" required>
                                        <p class="help-block help-block-error"></p>
                                </div>

                        </div>


                        <div class="form-group" style="float: right;">
                                <button type="submit" class="btn btn-primary" style="margin-top: 18px;">Change Password</button>    </div>
                                <?php ActiveForm::end(); ?>
                </div>

        </div>

</div>
<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script type="text/javascript">
                                $(document).ready(function() {
                                $("input").attr("required", "true");
                                        )};

