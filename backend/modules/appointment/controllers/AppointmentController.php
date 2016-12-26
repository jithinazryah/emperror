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
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * AppointmentController implements the CRUD actions for Appointment model.
 */
class AppointmentController extends Controller {

        public function init() {
                if (Yii::$app->user->isGuest)
                        $this->redirect(['/site/index']);

                if (Yii::$app->session['post']['appointments'] != 1) {
                        echo "<script>alert('You Have NoPermission to Access this Page');</script>";
                        $this->redirect(['/site/home']);
                }
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
                $model->setScenario('create');
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model) && $this->Principal($model, $_POST['Appointment']['principal'])) {
                        $files = UploadedFile::getInstance($model, 'final_draft_bl');
                        $model->final_draft_bl = $files->extension;
                        $model->eta = Yii::$app->ChangeDateFormate->SingleDateFormat($model->eta);
                        $model->save();
                        if (!empty($files)) {
                                $this->Upload($model, $files);
                        }
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

        public function Upload($model, $files) {
                $paths = Yii::$app->basePath . '/web/uploads/final_draft' . '/' . $model->id;
                if (!is_dir($paths)) {
                        mkdir($paths);
                }
                $path = Yii::$app->basePath . '/web/uploads/final_draft' . '/' . $model->id . '/' . $files->name;
//                if (file_exists($path)) {
//                        unlink($path);
//                }
                $files->saveAs($path);
                return true;
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
                        $model->eta = Yii::$app->ChangeDateFormate->SingleDateFormat($model->eta);
                        $files = UploadedFile::getInstance($model, 'final_draft_bl');
                        if (!empty($files)) {
                                $model->final_draft_bl = $files->extension;
                                $this->Upload($model, $files);
                        } else {
                                $model_data = Appointment::findOne($id);
                                $model->final_draft_bl = $model_data->final_draft_bl;
                        }
                        $model->save();
                        $model->eta = Yii::$app->ChangeDateFormate->SingleDateFormat($model->eta);
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        $model->eta = Yii::$app->ChangeDateFormate->SingleDateFormat($model->eta);
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

        /*
         * This function create entry in port call data tables when creating a new appointment
         */

        public function PortCall($model) {
                $port_data = new PortCallData();
                $port_draft = new PortCallDataDraft();
                $port_rob = new PortCallDataRob();
                $port_imigration = new ImigrationClearance();
                $port_data->appointment_id = $model->id;
                $port_data->eta = Yii::$app->ChangeDateFormate->SingleDateFormat($model->eta);
                $port_draft->appointment_id = $model->id;
                $port_rob->appointment_id = $model->id;
                $port_imigration->appointment_id = $model->id;

                if ($port_imigration->save() && $port_data->save() && $port_draft->save() && $port_rob->save()) {
                        return TRUE;
                } else {
                        return FALSE;
                }
        }

        /*
         * This function get principal from multiple select box and implode it with comma
         * return principal as a string
         */

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

        /*
         * This function generate appointment number basedon the previous appointment number
         * return appointment number
         */

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

        /*
         * This function select vessel type
         * return result to the view
         */

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

        /*
         * This function generate report for all the appointment
         */

        public function actionSearch() {
                $appointment = Appointment::find()->all();
                return $this->render('search', [
                            'appointment' => $appointment,
                ]);
        }

        /*
         * This unctio remove uploaded path
         */

        public function actionRemove($path) {
                if (Yii::$app->session['post']['id'] == 1) {
                        unlink($path);
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

}
