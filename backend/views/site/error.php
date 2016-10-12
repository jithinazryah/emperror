<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="page-error centered">

        <div class="error-symbol">
                <i class="fa-warning"></i>
        </div>

        <h2>
                <?= Html::encode($this->title) ?>
                <small><?= nl2br(Html::encode($message)) ?></small>
        </h2>

        <p>
                The above error occurred while the Web server was processing your request.
        </p>
        <p>
                Please contact us if you think this is a server error. Thank you.
        </p>
</div>
<!--<div class="site-error">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
                The above error occurred while the Web server was processing your request.
        </p>
        <p>
                Please contact us if you think this is a server error. Thank you.
        </p>

</div>-->
