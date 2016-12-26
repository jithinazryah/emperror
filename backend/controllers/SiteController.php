<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Employee;
use common\models\AdminPosts;
use kartik\mpdf\Pdf;
use common\models\ForgotPasswordTokens;

/**
 * Site controller
 */
class SiteController extends Controller {

        /**
         * @inheritdoc
         */
        public function behaviors() {
                return [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                                [
                                'actions' => ['login', 'error', 'index', 'home', 'report', 'forgot', 'new-password', 'exception'],
                                'allow' => true,
                            ],
                                [
                                'actions' => ['logout', 'index', 'Home', 'forgot', 'new'],
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                        ],
                    ],
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'logout' => ['post'],
                        ],
                    ],
                ];
        }

        /**
         * @inheritdoc
         */
        public function actions() {
                return [
                    'error' => [
                        'class' => 'yii\web\ErrorAction',
                    ],
                ];
        }

        /**
         * Displays homepage.
         *
         * @return string
         */
        public function actionIndex() {

                if (!Yii::$app->user->isGuest) {
                        return $this->redirect(array('site/home'));
                }
                $this->layout = 'login';
                $model = new Employee();
                $model->scenario = 'login';
                if ($model->load(Yii::$app->request->post()) && $model->login() && $this->setSession()) {
                        return $this->redirect(array('site/home'));
                } else {
                        return $this->render('login', [
                                    'model' => $model,
                        ]);
                }
        }

        public function setSession() {
                $post = AdminPosts::findOne(Yii::$app->user->identity->post_id);
                Yii::$app->session['post'] = $post->attributes;

                return true;
        }

        public function actionHome() {
                if (Yii::$app->user->isGuest) {
                        return $this->redirect(array('site/index'));
                }
                return $this->render('index');
        }

        /**
         * Login action.
         *
         * @return string
         */
        public function actionLogin() {
                $this->layout = 'login';
                if (!Yii::$app->user->isGuest) {
                        return $this->goHome();
                }

                $model = new LoginForm();
                if ($model->load(Yii::$app->request->post()) && $model->login()) {
                        return $this->goBack();
                } else {
                        return $this->render('login', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Logout action.
         *
         * @return string
         */
        public function actionLogout() {
                Yii::$app->user->logout();
                unset(Yii::$app->session['post']);
                return $this->goHome();
        }

        public function actionReport() {
                // get your HTML raw content without any layouts or scripts
                $content = $this->renderPartial('pdf');

                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    //'mode' => Pdf::MODE_CORE,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
                    'destination' => Pdf::DEST_BROWSER,
                    // your html content input
                    'content' => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting
                    'cssFile' => '@backend/web/css/pdf.css',
                    // any css to be embedded if required
                    //'cssInline' => '.kv-heading-1{font-size:18px}',
                    // set mPDF properties on the fly
                    //'options' => ['title' => 'Krajee Report Title'],
                    // call mPDF methods on the fly
                    'methods' => [
                        'SetHeader' => ['Krajee Report Header' . date("y-m-d h:m:s")],
                        'SetFooter' => ['|page {PAGENO}'],
                    ]
                ]);

                // return the pdf output as per the destination setting
                return $pdf->render();
        }

        public function actionForgot() {
                $this->layout = 'login';
                $model = new Employee();
                if ($model->load(Yii::$app->request->post())) {
                        $check_exists = Employee::find()->where("user_name = '" . $model->user_name . "' OR email = '" . $model->user_name . "'")->one();
                        if (!empty($check_exists)) {
                                $token_value = $this->tokenGenerator();
                                $token = $check_exists->id . '_' . $token_value;
                                $val = base64_encode($token);
                                $token_model = new ForgotPasswordTokens();
                                $token_model->user_id = $check_exists->id;
                                $token_model->token = $token_value;
                                $token_model->save();
                                $this->sendMail($val, $check_exists->email);
                                Yii::$app->getSession()->setFlash('success', 'A mail has been sent');
                        } else {
                                Yii::$app->getSession()->setFlash('error', 'Invalid username');
                        }
                        return $this->render('forgot-password', [
                                    'model' => $model,
                        ]);
                } else {
                        return $this->render('forgot-password', [
                                    'model' => $model,
                        ]);
                }
        }

        public function tokenGenerator() {

                $length = rand(1, 1000);
                $chars = array_merge(range(0, 9));
                shuffle($chars);
                $token = implode(array_slice($chars, 0, $length));
                return $token;
        }

        public function sendMail($val, $email) {

//        echo '<a href="' . Yii::$app->homeUrl . 'site/new-password?token=' . $val . '">Click here change password</a>';
//        exit;
                $to = $email;

// subject
                $subject = 'Change password';

// message
//                echo
                $message = '
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>
  <p>Change Password</p>
  <table>

     <tr>
      <td><a href="' . Yii::$app->getRequest()->serverName . Yii::$app->homeUrl . 'site/new-password?token=' . $val . '">Click here change password</a></td>
    </tr>

  </table>
</body>
</html>
';
//                exit;
// To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                mail($to, $subject, $message, $headers);
        }

        public function actionNewPassword($token) {
                $this->layout = 'login';
                $data = base64_decode($token);
                $values = explode('_', $data);
                $token_exist = ForgotPasswordTokens::find()->where("user_id = " . $values[0] . " AND token = " . $values[1])->one();
                if (!empty($token_exist)) {
                        $model = Employee::find()->where("id = " . $token_exist->user_id)->one();
                        if (Yii::$app->request->post()) {
                                if (Yii::$app->request->post('new-password') == Yii::$app->request->post('confirm-password')) {
                                        Yii::$app->getSession()->setFlash('success', 'password changed successfully');
                                        $model->password = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('confirm-password'));
                                        $model->update();
                                        $token_exist->delete();
                                        $this->redirect('index');
                                } else {
                                        Yii::$app->getSession()->setFlash('error', 'password mismatch  ');
                                }
                        }
                        return $this->render('new-password', [
                        ]);
                } else {

                }
        }

        public function actionException() {
                return $this->render('exception');
        }

}
