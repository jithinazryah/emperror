<html>
        <head>
        </head>
        <body>
                <div class="col-lg-12">
                        <?php if (Yii::$app->session->hasFlash('exception')): ?>
                                <div class="alert alert-danger" role="alert">
                                        <?= Yii::$app->session->getFlash('exception') ?>
                                </div>
                        <?php endif; ?>
                </div>
        </body>

</html>

