<?php

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\EstimatedProforma;
use common\models\SubServices;
use common\models\Appointment;
use common\models\MasterSubService;
use common\models\EstimatedProformaSearch;
use common\models\AppointmentSearch;
use common\models\EstimateReport;
use common\models\Debtor;
use common\models\UploadFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use common\models\Services;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\FundingAllocation;

/**
 * EstimatedProformaController implements the CRUD actions for EstimatedProforma model.
 */
class EstimatedProformaController extends Controller {

        public function init() {
                if (Yii::$app->user->isGuest)
                        $this->redirect(['/site/index']);

                if (Yii::$app->session['post']['estimated_proforma'] != 1) {
                        Yii::$app->getSession()->setFlash('exception', 'You have no permission to access this page');
                        $this->redirect(['/site/exception']);
                }
        }

        /**
         * @inheritdoc
         */
        public function behaviors() {
                return [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        // 'reports' => ['POST'],
                        ],
                    ],
                ];
        }

        /**
         * Lists all EstimatedProforma models.
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
         * Displays a single EstimatedProforma model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new EstimatedProforma model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new EstimatedProforma();

                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /*
         * Create new Estimated Proforma and Update an existing Estimated Proforma model.
         */

        public function actionAdd($id, $prfrma_id = NULL, $check = NULL) {
                if (Yii::$app->session['post']['estimated_proforma'] != 1) {
                        Yii::$app->getSession()->setFlash('exception', 'You have no permission to access this page');
                        $this->redirect(['/site/exception']);
                }
                $estimates = EstimatedProforma::findAll(['apponitment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $model_upload = new UploadFile();
                if ($appointment->status == 0) {
                        return $this->render('_closed', [
                                    'estimates' => $estimates,
                                    'appointment' => $appointment,
                                    'id' => $id,
                        ]);
                }
                if (empty($estimates) && !empty($check)) {
                        $this->CheckPerforma($id, $appointment);
                        $estimates = EstimatedProforma::findAll(['apponitment_id' => $id]);
                }
                if (!isset($prfrma_id)) {
                        $model = new EstimatedProforma;
                } else {
                        $model = $this->findModel($prfrma_id);
                }
                if ($model->load(Yii::$app->request->post()) && $this->SetValues($model, $id)) {
                        $model->epda = $model->unit_rate * $model->unit;
                        $service_category = Services::findOne(['id' => $model->service_id]);
                        $model->service_category = $service_category->category_id;
                        if ($model->save()) {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                }

                return $this->render('add', [
                            'model' => $model,
                            'estimates' => $estimates,
                            'appointment' => $appointment,
                            'id' => $id,
                            'model_upload' => $model_upload,
                ]);
        }

        /*
         * This function upload multiple fies in estimated proforma
         */

        public function actionUploads() {
                $model_upload = new UploadFile();
                if ($model_upload->load(Yii::$app->request->post())) {
                        $files = UploadedFile::getInstances($model_upload, 'filee');
                        if (Yii::$app->UploadFile->Upload($files, $model_upload)) {

                                return $this->redirect(Yii::$app->request->referrer);
                        }
                }
        }

        /*
         * This function will check the estimated performa contain estmates on the same principal
         * If it contain Load it into the new appointment
         */

        public function CheckPerforma($id, $appointment) {
                if ($appointment->vessel_type != 1) {
                        $appntment = Appointment::find()->where('id != :id and principal = :principal and DOC < NOW() and vessel =:vessel', ['id' => $id, 'principal' => $appointment->principal, 'vessel' => $appointment->vessel])->orderBy(['id' => SORT_DESC])->all();
                } else {
                        $appntment = Appointment::find()->where('id != :id and principal = :principal and DOC < NOW() and tug =:tug and barge =:barge', ['id' => $id, 'principal' => $appointment->principal, 'tug' => $appointment->tug, 'barge' => $appointment->barge])->orderBy(['id' => SORT_DESC])->all();
                }
                if (empty($appntment)) {
                        $appntment = Appointment::find()->where('id != :id and principal = :principal and DOC < NOW() and vessel_type =:vessel_type', ['id' => $id, 'principal' => $appointment->principal, 'vessel_type' => $appointment->vessel_type])->orderBy(['id' => SORT_DESC])->all();
                }
                foreach ($appntment as $ar) {
                        $performa_check = EstimatedProforma::findAll(['apponitment_id' => $ar->id]);
                        if (!empty($performa_check)) {
                                $this->SetData($performa_check, $id);
                                break;
                                return true;
                        }
                }
        }

        /*
         * This function delete estimated performa based on the esimate id
         */

        public function actionDeletePerforma($id) {
                $this->findModel($id)->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

        /**
         * Updates an existing EstimatedProforma model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model, $id)) {
                        $model->epda = $model->unit_rate * $model->unit;
                        $model->save();
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing EstimatedProforma model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                //return $this->redirect(['index']);
                return $this->refresh();
        }

        /**
         * Finds the EstimatedProforma model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return EstimatedProforma the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = EstimatedProforma::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        /*
         * This action will called when complete the estimated proforma
         * This action change the aoppintment number and save it in the appointment
         * return to the port call data update
         */

        public function actionEstimateConfirm($id) {
                $appointment = Appointment::findOne($id);
                $new_appid = substr($appointment->appointment_no, 2);
                $old_appid = substr($appointment->appointment_no, 0, 2);
                $estimates = EstimatedProforma::findAll(['apponitment_id' => $id]);
                $this->UpdateFundAllocation($id, $appointment);
                if (!empty($estimates)) {
                        if ($old_appid == 'EN') {
                                $appointment->appointment_no = $new_appid;
                        }
                        $appointment->save();
                        return $this->redirect(['/appointment/port-call-data/update', 'id' => $appointment->id]);
                } else {
                        Yii::$app->getSession()->setFlash('error', 'Estimated Proforma Not Completed..');
                        return $this->redirect(['add', 'id' => $id]);
                }
        }

        /*
         * After complete estimated froforma ,update the fund allocation
         */

        protected function UpdateFundAllocation($id, $appointment) {
                $principp = explode(',', $appointment->principal);
                foreach ($principp as $value) {
                        $estimates = EstimatedProforma::findAll(['apponitment_id' => $id, 'principal' => $value]);
                        $model_fund = FundingAllocation::findOne(['appointment_id' => $id, 'principal_id' => $value, 'type' => 3]);
                        $epda_total = 0;
                        foreach ($estimates as $estimate) {
                                $epda_total += $estimate->epda;
                        }
                        if (!empty($model_fund)) {
                                $model_fund->outstanding = $epda_total;
                        } else {
                                $model_fund = new FundingAllocation;
                                $model_fund->appointment_id = $id;
                                $model_fund->fund_date = date('Y-m-d h:m:s');
                                $model_fund->outstanding = $epda_total;
                                $model_fund->type = '3';
                                $model_fund->principal_id = $value;
                                Yii::$app->SetValues->Attributes($model_fund);
                        }
                        $model_fund->save();
                }
        }

        /*
         * This function set CB,UB,DOC
         */

        protected function SetValues($model, $id) {

                if (Yii::$app->SetValues->Attributes($model)) {
                        $model->setAttribute('apponitment_id', $id);
                        return true;
                } else {
                        return false;
                }
        }

        /*
         * This function set previous estimated proforma to the new appoinment
         */

        protected function SetData($performa_check, $id) {
                foreach ($performa_check as $pfrma) {
                        $value = EstimatedProforma::find()->where(['id' => $pfrma->id])->one();
                        $model = new EstimatedProforma;
                        $auto_id = $value->id;
                        $model->apponitment_id = $id;
                        $model->service_id = $value->service_id;
                        $model->service_category = $value->service_category;
                        $model->supplier = $value->supplier;
                        $model->unit_rate = $value->unit_rate;
                        $model->unit = $value->unit;
                        $model->epda = $value->epda;
                        $model->principal = $value->principal;
                        $model->invoice_type = $value->invoice_type;
                        $model->comments = $value->comments;
                        $model->rate_to_category = $value->rate_to_category;
                        $model->status = $value->status;
                        $model->CB = Yii::$app->user->identity->id;
                        $model->DOC = date('Y-m-d');
                        $model->save();
                        $new_id = $model->id;
                        $sub_service = SubServices::find()->where(['estid' => $auto_id])->all();
                        if (!empty($sub_service)) {
                                $this->SetSubService($sub_service, $new_id, $model->apponitment_id);
                        }
                }
                return TRUE;
        }

        /*
         * This function load previously generated subservices
         */

        protected function SetSubService($sub_service, $new_id, $appointment_id) {
                foreach ($sub_service as $value) {

                        $model = new SubServices;
                        $model->appointment_id = $appointment_id;
                        $model->estid = $new_id;
                        $model->service_id = $value->service_id;
                        $model->sub_service = $value->sub_service;
                        $model->unit = $value->unit;
                        $model->unit_price = $value->unit_price;
                        $model->total = $value->total;
                        $model->comments = $value->comments;
                        $model->rate_to_category = $value->rate_to_category;
                        $model->status = $value->status;
                        $model->save(false);
                }
                return true;
        }

        /*
         * This function find the supplier for a particular service
         * return result to the 'add' view
         */

        public function actionSupplier() {
                if (Yii::$app->request->isAjax) {
                        $service_id = $_POST['service_id'];
                        $services_data = \common\models\Services::find()->where(['id' => $service_id])->one();
                        if ($services_data->supplier_options == 1) {
                                $supplier_datas = \common\models\Contacts::findAll(['status' => 1, 'id' => explode(',', $services_data->supplier)]);
                                $options = '<option value="">-Supplier-</option>';
                                foreach ($supplier_datas as $supplier_data) {
                                        $options .= "<option value='" . $supplier_data->id . "'>" . $supplier_data->name . "</option>";
                                }
                                echo $options;
                        }
                }
        }

        /*
         * This function find the 'sub services' for a particular service
         * return result to the 'add' view
         */

        public function actionSubservice() {
                if (Yii::$app->request->isAjax) {
                        $service_id = $_POST['service_id'];
                        $services_datas = \common\models\MasterSubService::findAll((['service_id' => $service_id, 'status' => 1]));
                        $options = '<option value="">-Sub Service-</option>';
                        foreach ($services_datas as $services_data) {
                                $options .= "<option value='" . $services_data->id . "'>" . $services_data->sub_service . "</option>";
                        }
                        echo $options;
                }
        }

        /*
         * It generate estimate report based on principal
         */

        public function actionReports() {
                $princip = $_POST['principal'];
                $app = $_POST['app_id'];
                $appointment = Appointment::findOne($app);
                Yii::$app->session->set('epda', $this->renderPartial('report', [
                            'appointment' => $appointment,
                            //'estimates' => $estimates,
                            'princip' => $princip,
                            'save' => false,
                            'print' => true,
                ]));
                echo $this->renderPartial('report', [
                    'appointment' => $appointment,
                    //'estimates' => $estimates,
                    'princip' => $princip,
                    'save' => true,
                    'print' => false,
                ]);
                exit;
        }

        /*
         * It removes the uploaded files from directory
         */

        public function actionRemove($path) {
                if (Yii::$app->session['post']['id'] == 1) {
                        unlink($path);
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        /*
         * This function save the generate Estimated Proforma report
         */

        public function actionSaveReport($id) {
                $model_report = new EstimateReport();
                $model_report->appointment_id = $id;
                $model_report->report = Yii::$app->session['epda'];
                $model_report->status = 1;
                Yii::$app->SetValues->Attributes($model_report);
                $model_report->save();
                echo "<script>window.close();</script>";
                exit;
        }

        /*
         * This function shoe the saved reports
         */

        public function actionShowReport($id) {
                $model_report = EstimateReport::findOne($id);
                $model_report->report;
                return $this->renderPartial('_old', [
                            'model_report' => $model_report,
                ]);
        }

        /*
         * This function remove the saved estimate report
         */

        public function actionRemoveReport($id) {
                if (Yii::$app->session['post']['id'] == 1) {
                        EstimateReport::findOne($id)->delete();
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        /*
         * This function save the estimated proforma text field values on double click
         */

        public function actionEditEstimate() {
                if (Yii::$app->request->isAjax) {
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $value = $_POST['valuee'];
                        $estimate = EstimatedProforma::find()->where(['id' => $id])->one();
                        if ($name == 'unit' || $name == 'unit_rate') {
                                if ($name == 'unit') {
                                        $estimate->epda = $estimate->unit_rate * $value;
                                } else {
                                        $estimate->epda = $estimate->unit * $value;
                                }
                        }
                        if ($value != '') {
                                $estimate->$name = $value;
                                if ($estimate->save()) {
                                        return 1;
                                } else {
                                        return 2;
                                }
                        }
                }
        }

        public function actionEditEstimateService() {
                if (Yii::$app->request->isAjax) {
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $value = $_POST['valuee'];
                        $estimate = EstimatedProforma::find()->where(['id' => $id])->one();
                        if ($value != '') {
                                $estimate->$name = $value;
                                if ($estimate->save()) {
                                        if ($name == 'principal') {
                                                $principals = Debtor::find()->where(['id' => $value])->one();
                                                $options = "<option value='" . $principals->id . "'>" . $principals->principal_id . "</option>";
                                        }
                                        echo $options;
                                }
                        }
                }
        }

}
