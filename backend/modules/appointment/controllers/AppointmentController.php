<?php

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\Appointment;
use common\models\Ports;
use common\models\PortCallData;
use common\models\PortCallDataDraft;
use common\models\PortCallDataRob;
use common\models\EstimatedProforma;
use common\models\CloseEstimate;
use common\models\AppointmentSearch;
use common\models\PortBreakTimings;
use common\models\Currency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ImigrationClearance;

/**
 * AppointmentController implements the CRUD actions for Appointment model.
 */
class AppointmentController extends Controller {

        public function init() {
                if (Yii::$app->user->isGuest)
                        $this->redirect(['/site/index']);

                if (Yii::$app->session['post']['admin'] != 1)
                        $this->redirect(['/site/home']);
        }

        /**
         * @inheritdoc
         */
        public function behaviors() {
                return [
                    'access' => [
                        'class' => AccessControl::className(),
                        'only' => ['addBasic'],
                        'rules' => [
                                [
                                'actions' => ['appointmentNo'],
                                'allow' => true,
                                'roles' => ['?'],
                            ],
                        ],
                    ],
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ];
        }

        /**
         * Lists all Appointment models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new AppointmentSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single Appointment model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                $estimates = EstimatedProforma::findAll(['apponitment_id' => $id]);
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $closeestimates = CloseEstimate::findAll(['apponitment_id' => $id]);
                $drafts = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $imigration = ImigrationClearance::findOne(['appointment_id' => $id]);
                return $this->render('view', [
                            'model' => $this->findModel($id),
                            'estimates' => $estimates,
                            'appointment' => $appointment,
                            'ports' => $ports,
                            'drafts' => $drafts,
                            'rob' => $rob,
                            'closeestimates' => $closeestimates,
                            'imigration' => $imigration,
                ]);
        }

        /**
         * Creates a new Appointment model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new Appointment();
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model) && $this->Principal($model, $_POST['Appointment']['principal'])) {
//                        $model->stage = 1;
//                        $model->sub_stages = 1;
                        $model->eta = $this->SingleDateFormat($model->eta);
                        $model->save();
                        $this->PortCall($model);
                        if (!empty(Yii::$app->request->post(check))) {
                                return $this->redirect(['/appointment/estimated-proforma/add', 'id' => $model->id, 'check' => true]);
                        } else {
                                return $this->redirect(['/appointment/estimated-proforma/add', 'id' => $model->id]);
                        }
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing Appointment model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);

                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model) && $this->Principal($model, $_POST['Appointment']['principal'])) {
                        $model->eta = $this->SingleDateFormat($model->eta);
                        $model->save();
                        $model->eta = $this->SingleDateFormat($model->eta);
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        $model->eta = $this->SingleDateFormat($model->eta);
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        public function actionDisable($id) {
                $model = $this->findModel($id);
                $model->status = 0;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
        }

        public function PortCall($model) {
                $port_data = new PortCallData();
                $port_draft = new PortCallDataDraft();
                $port_rob = new PortCallDataRob();
                $port_imigration = new ImigrationClearance();
                $port_data->appointment_id = $model->id;
                $port_data->eta = $this->SingleDateFormat($model->eta);
                $port_draft->appointment_id = $model->id;
                $port_rob->appointment_id = $model->id;
                $port_imigration->appointment_id = $model->id;

                if ($port_imigration->save() && $port_data->save() && $port_draft->save() && $port_rob->save()) {
                        return TRUE;
                } else {
                        return FALSE;
                }
        }

        public function Principal($model, $principle) {
                if ($model != null && $principle != '') {
                        $model->principal = implode(",", $principle);
                        Yii::$app->SetValues->Attributes($model);
                        return TRUE;
                } else {
                        return FALSE;
                }
        }

        /**
         * Deletes an existing Appointment model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the Appointment model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return Appointment the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = Appointment::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        public function actionAppointmentNo() {
                if (Yii::$app->request->isAjax) {
                        $port_id = $_POST['port_id'];
                        $port_data = Ports::find()->where(['id' => $port_id])->one();
                        $last_appointment = Appointment::find()->orderBy(['id' => SORT_DESC])->where(['port_of_call' => $port_id])->one();
                        if (empty($last_appointment))
                                echo 'EN' . $port_data->code . '0001';
                        else {
                                $last = substr($last_appointment->appointment_no, -4);
                                $last = ltrim($last, '0');

                                echo 'EN' . $port_data->code . (sprintf('%04d', ++$last));
                        }
                } else {
                        return '';
                }
        }

        public function actionVesselType() {
                if (Yii::$app->request->isAjax) {
                        $vessel_type = $_POST['vessel_type'];
                        $vessel_datas = \common\models\Vessel::findAll(['vessel_type' => $vessel_type, 'status' => 1]);
                        $options = '<option value="">-Choose a Vessel-</option>';
                        foreach ($vessel_datas as $vessel_data) {
                                $options .= "<option value='" . $vessel_data->id . "'>" . $vessel_data->vessel_name . "</option>";
                        }

                        echo $options;
                }
        }

        public function ChangeFormat($data) {

                $day = substr($data, 0, 2);
                $month = substr($data, 2, 2);
                $year = substr($data, 4, 4);
                $hour = substr($data, 9, 2) == '' ? '00' : substr($data, 9, 2);
                $min = substr($data, 11, 2) == '' ? '00' : substr($data, 11, 2);
                $sec = substr($data, 13, 2) == '' ? '00' : substr($data, 13, 2);
                if ($hour != '00' && $min != '00' && $sec != '00') {
                        //echo '1';exit;
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
                } elseif ($hour == '00' && $min != '00') {
                        //echo '2';exit;
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                } elseif ($hour != '00' && $min != '00') {
                        //echo '2';exit;
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                } elseif ($hour != '00') {
                        //echo '3';exit;
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':00';
                } else {

                        return $year . '-' . $month . '-' . $day;
                }
        }

        public function SingleDateFormat($dta) {
                if (strpos($dta, '-') == false) {

                        if (strlen($dta) < 16 && strlen($dta) >= 8 && $dta != NULL)
                                return $this->ChangeFormat($dta);
                        //echo $model->$key;exit;
                }else {
                        $year = substr($dta, 0, 4);
                        $month = substr($dta, 5, 2);
                        $day = substr($dta, 8, 2);
                        $hour = substr($dta, 11, 2) == '' ? '00' : substr($dta, 11, 2);
                        $min = substr($dta, 14, 2) == '' ? '00' : substr($dta, 14, 2);
                        $sec = substr($dta, 17, 2) == '' ? '00' : substr($dta, 17, 2);

                        if ($hour != '00' && $min != '00' && $sec != '00') {
                                return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
                        } elseif ($hour == '00' && $min != '00') {
                                //echo '2';exit;
                                return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                        } elseif ($hour != '00' && $min != '00') {
                                return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                        } elseif ($hour != '00') {
                                return $year . '-' . $month . '-' . $day . ' ' . $hour . ':00';
                        } else {
                                return $year . '-' . $month . '-' . $day;
                        }
                }
        }

        public function actionRepport() {
                $from = $_POST['from'];
                $to = $_POST['to'];
                $searchModel = new AppointmentSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                if ($from != '' && $to != '') {
                        $dataProvider->query->where(['between', 'DOC', $from, $to]);
                }
                return $this->render('report', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        public function actionReport() {
                $searchModel = new AppointmentSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                return $this->render('report', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        public function actionSearch() {
                $appointment = Appointment::find()->all();
                return $this->render('search', [
                            'appointment' => $appointment,
                ]);
        }

}
