<?php

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\CloseEstimate;
use common\models\Appointment;
use common\models\CloseEstimateSearch;
use common\models\AppointmentSearch;
use common\models\PortCallData;
use yii\web\Controller;
use common\models\UploadFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\EstimatedProforma;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\SubServices;
use common\models\CloseEstimateSubService;
use common\models\Services;
use common\models\InvoiceType;
use common\models\Debtor;
use common\models\FundingAllocation;
use common\models\InvoiceNumber;
use common\models\FdaReport;
use common\models\Ports;

/**
 * CloseEstimateController implements the CRUD actions for CloseEstimate model.
 */
class CloseEstimateController extends Controller {

        public function init() {
                if (Yii::$app->user->isGuest)
                        $this->redirect(['/site/index']);

                if (Yii::$app->session['post']['close_estimate'] != 1) {
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
                        ],
                    ],
                ];
        }

        /**
         * Lists all CloseEstimate models.
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
         * Displays a single CloseEstimate model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new CloseEstimate model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new CloseEstimate();
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /*
         * Create new Close Estimate and Update an existing CloseEstimate model.
         */

        public function actionAdd($id, $prfrma_id = NULL) {
                if (Yii::$app->session['post']['close_estimate'] != 1) {
                        Yii::$app->getSession()->setFlash('exception', 'You have no permission to access this page');
                        $this->redirect(['/site/exception']);
                }
                $estimates = CloseEstimate::findAll(['apponitment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $model_upload = new UploadFile();
                if ($appointment->status == 0) {
                        return $this->render('_closed', [
                                    'estimates' => $estimates,
                                    'appointment' => $appointment,
                                    'id' => $id,
                        ]);
                }
                if (!isset($prfrma_id)) {
                        $model = new CloseEstimate;
                } else {
                        $model = $this->findModel($prfrma_id);
                }

                if ($model->load(Yii::$app->request->post()) && $this->SetValues($model, $id)) {
                        $model->fda = $model->unit_rate * $model->unit;
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
         * Restore Estimated Proforma Values into Close Estimate
         */

        public function actionInsertCloseEstimate($id) {
                $estimates = EstimatedProforma::findAll(['apponitment_id' => $id]);
                foreach ($estimates as $estimate) {
                        $model = new CloseEstimate;
                        $model->apponitment_id = $id;
                        $model->service_id = $estimate->service_id;
                        $model->supplier = $estimate->supplier;
                        $model->unit_rate = $estimate->unit_rate;
                        $model->unit = $estimate->unit;
                        $model->epda = $estimate->epda;
                        $model->principal = $estimate->principal;
                        $model->invoice_type = $estimate->invoice_type;
                        $model->comments = $estimate->comments;
                        $model->status = $estimate->status;
                        $model->CB = Yii::$app->user->identity->id;
                        $model->UB = Yii::$app->user->identity->id;
                        $model->fda = $estimate->epda;
                        $model->DOC = date('Y-m-d');
                        $model->save();
                        //echo $model->id;exit;
                        $close_estimate_sub_services = CloseEstimateSubService::findAll(['close_estimate_id' => $model->id]);
                        if (empty($close_estimate_sub_services)) {
                                $estimate_sub_services = SubServices::findAll(['estid' => $estimate->id]);
                                if (!empty($estimate_sub_services)) {
                                        $this->AddSubService($estimate_sub_services, $model->id, $id);
                                }
                        }
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        /*
         * Load  Estimated Proforma subservices  Values into Close Estimate Subservices
         */

        public function AddSubService($estimate_sub_services, $id, $appointment_id) {
                foreach ($estimate_sub_services as $sub_service) {
                        $model = new CloseEstimateSubService();
                        $model->appointment_id = $appointment_id;
                        $model->close_estimate_id = $id;
                        $model->service_id = $sub_service->service_id;
                        $model->sub_service = $sub_service->id;
                        $model->unit = $sub_service->unit;
                        $model->unit_price = $sub_service->unit_price;
                        $model->total = $sub_service->total;
                        $model->comments = $sub_service->comments;
                        $model->status = $sub_service->status;
                        $model->CB = Yii::$app->user->identity->id;
                        $model->UB = Yii::$app->user->identity->id;
                        $model->DOC = date('Y-m-d');
                        $model->save(false);
                }
                return true;
        }

        /*
         * Delete Closeestimate
         * If delete is successful, the browser will be redirected to the 'add' page.
         */

        public function actionDeleteCloseEstimate($id) {

                $this->findModel($id)->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

        /**
         * Updates an existing CloseEstimate model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model, $id) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing CloseEstimate model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the CloseEstimate model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return CloseEstimate the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = CloseEstimate::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        /*
         * It will set CB,UB,DOC values by calling the controller function StValues
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
         * This will find the supplier options from Services Table
         * return supplier_optionns
         */

        public function actionSupplier() {
                if (Yii::$app->request->isAjax) {
                        $service_id = $_POST['service_id'];
                        $services_data = \common\models\Services::find()->where(['id' => $service_id])->one();
                        echo $services_data->supplier_options;
                }
        }

        /*
         * Function for Multiple File Upload
         * return to the 'add' view page
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
         * Generate Close Estimate Report depends on principal
         * It generate report for all invoice type with same principal
         * if principal is not mentioned it will generate report on all the data in the close estimte
         */

        public function actionReport() {
                empty(Yii::$app->session['fda-report']);
                $invoice_type = $_POST['invoice_type'];
                $app = $_POST['app_id'];
                $principp = $_POST['fda'];
                $invoice_date = Yii::$app->ChangeDateFormate->SingleDateFormat($_POST['invoice_date']);
                $appointment = Appointment::findOne($app);
                $ports = PortCallData::findOne(['appointment_id' => $app]);
                $princip = CloseEstimate::findAll(['principal' => $principp, 'apponitment_id' => $app]);

                echo $this->renderPartial('report', [
                    'appointment' => $appointment,
                    'invoice_type' => $invoice_type,
                    'princip' => $princip,
                    'ports' => $ports,
                    'principp' => $principp,
                    'invoice_date' => $invoice_date,
                    'save' => true,
                    'print' => false,
                ]);
                Yii::$app->session->set('fda-report', $this->renderPartial('report', [
                            'appointment' => $appointment,
                            'invoice_type' => $invoice_type,
                            'princip' => $princip,
                            'ports' => $ports,
                            'principp' => $principp,
                            'invoice_date' => $invoice_date,
                            'save' => false,
                            'print' => true,
                ]));
                exit;
        }

        /*
         * This function save close estimate report generated based on principal
         * return to the close-estimate 'add' view page
         */

        public function actionSaveAllReport($appintment_id, $principal_id) {
                $model_report = $this->InvoiceGeneration($appintment_id, $principal_id);
                Yii::$app->SetValues->Attributes($model_report);
                if ($model_report->save(false)) {
                        $this->UpdateFundAllocation($appintment_id, $principal_id);
                        echo "<script>window.close();</script>";
                        exit;
                }
        }

        /*
         * This function generate oops reference number for report
         * This function is called from 'report' view
         * return oops reference number
         */

        public function oopsNo($data_principal, $principp) {
                $arr = ['0' => '', '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L'];
                $data = explode(',', $data_principal);
                $j = 0;
                foreach ($data as $value) {
                        if ($value == $principp) {
                                foreach ($arr as $key => $value) {
                                        if ($key == $j) {
                                                return $value;
                                        }
                                }
                        }
                        $j++;
                }
        }

        /*
         * This function generate invoice number for All report
         * this function is called from 'report' view and 'SaveAllReport' action in close-estimate controller
         * return model
         */

        public function InvoiceGeneration($appintment_id, $principal_id) {

                $appointment = Appointment::findOne($appintment_id);
                $last_data = FdaReport::find()->orderBy(['id' => SORT_DESC])->where(['principal_id' => $principal_id])->one();
                $last_report_saved = FdaReport::find()->orderBy(['id' => SORT_DESC])->where(['appointment_id' => $appintment_id, 'principal_id' => $principal_id])->one();
                $port_code = Ports::findOne($appointment->port_of_call)->code;
                if ($principal_id != '') {
                        $princip_id = Debtor::findOne($principal_id)->principal_id;
                } else {
                        $princip_id = Debtor::findOne($appointment->principal)->principal_id;
                }
                $new_port_code = substr($port_code, -3);
                $app_no = ltrim(substr($appointment->appointment_no, -4), '0');
                $invoice_number = $new_port_code . '-' . $app_no . '-' . $princip_id . '-' . date("y");
                $model_report = new FdaReport();
                $model_report->appointment_id = $appintment_id;
                $model_report->principal_id = $principal_id;
                $model_report->invoice_number = $invoice_number;
                $model_report->report = Yii::$app->session['fda-report'];
                if (empty($last_data)) {
                        $model_report->sub_invoice = 124;
                } else {
                        if (empty($last_report_saved)) {
                                $model_report->sub_invoice = $last_data->sub_invoice + 1;
                        } else {
                                $model_report->sub_invoice = $last_report_saved->sub_invoice;
                        }
                }
                return $model_report;
        }

        /*
         * This function shows saved All reports
         */

        public function actionShowAllReport($id) {
                $model_report = FdaReport::findOne($id);
                $model_report->report;
                return $this->renderPartial('_old', [
                            'model_report' => $model_report,
                ]);
        }

        /*
         * This function remove saved report from Fda report Table
         */

        public function actionRemoveAllReport($id) {
                if (Yii::$app->session['post']['id'] == 1) {
                        FdaReport::findOne($id)->delete();
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        /*
         * Update Funding allocation when Saving  final DA
         * This function performing only when generating all report based on principal
         */

        protected function UpdateFundAllocation($id, $principp) {
                $close_estimates = CloseEstimate::findAll(['apponitment_id' => $id, 'principal' => $principp]);
                $model_fund = FundingAllocation::findOne(['appointment_id' => $id, 'principal_id' => $principp, 'type' => 4]);
                $fda_total = 0;
                foreach ($close_estimates as $estimate) {
                        $fda_total += $estimate->fda;
                }
                if (!empty($model_fund)) {
                        $model_fund->outstanding = $fda_total;
                        $model_fund->UB = Yii::$app->user->identity->id;
                } else {
                        $model_fund = new FundingAllocation;
                        $model_fund->appointment_id = $id;
                        $model_fund->fund_date = date('Y-m-d h:m:s');
                        $model_fund->outstanding = $fda_total;
                        $model_fund->type = '4';
                        $model_fund->principal_id = $principp;
                        Yii::$app->SetValues->Attributes($model_fund);
                }
                $model_fund->save();
        }

        /*
         * Remove the uploaded data path
         */

        public function actionRemove($path) {
                if (Yii::$app->session['post']['id'] == 1) {
                        unlink($path);
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        /*
         * This function generate FDA for individual or selected close estimate
         * This function is performed based on invoice type
         */

        public function actionSelectedReport() {
                $appointment_id = $_POST['app_id'];
                $appointment = Appointment::findOne($appointment_id);
                $ports = PortCallData::findOne(['appointment_id' => $appointment_id]);
                if (!empty($_POST['invoice_type'])) {
                        $est_id = array();
                        $invoice = array();
                        foreach ($_POST['invoice_type'] as $key => $value) {
                                $est_id[] = $key;
                                $invoice[] = $value;
                        }
                        if ($invoice[0] == '') {
                                $error = 'Invoice type field cannot be blank';
                                return $this->renderPartial('error', [
                                            'error' => $error,
                                ]);
                        }
                        if (count(array_unique($invoice)) === 1) {
                                $princip = CloseEstimate::findOne(['apponitment_id' => $appointment_id, 'id' => $est_id[0]]);
                                $close_estimates = CloseEstimate::findAll(['invoice_type' => $princip->invoice_type, 'apponitment_id' => $appointment_id, 'id' => $est_id]);
                                $this->GenerateReport($close_estimates, $appointment, $invoice, $princip, $ports, $est_id);
                        } else {
                                $error = 'Choose Same Invoice Type';
                                return $this->renderPartial('error', [
                                            'error' => $error,
                                ]);
                        }
                }
                exit;
        }

        /*
         * This function will generate individual FDA report
         */

        protected function GenerateReport($close_estimates, $appointment, $invoice, $princip, $ports, $est_id) {
                if (!empty($close_estimates)) {
                        $flag = 0;
                        foreach ($close_estimates as $close_estimate) {
                                if ($close_estimate->status == 1) {
                                        $flag = 1;
                                }
                        }
                        if ($flag == 1) {
                                $error = 'Already generate FDA on this esimate';
                                echo $this->renderPartial('error', [
                                    'error' => $error,
                                ]);
                                exit;
                        }
                }
                Yii::$app->session->set('fda', $this->renderPartial('fda_report', [
                            'appointment' => $appointment,
                            'close_estimates' => $close_estimates,
                            'invoice' => $invoice,
                            'princip' => $princip,
                            'ports' => $ports,
                            'est_id' => $est_id,
                            'save' => false,
                            'print' => true,
                ]));
                echo $this->renderPartial('fda_report', [
                    'appointment' => $appointment,
                    'close_estimates' => $close_estimates,
                    'invoice' => $invoice,
                    'princip' => $princip,
                    'ports' => $ports,
                    'est_id' => $est_id,
                    'save' => true,
                    'print' => false,
                ]);

                exit;
        }

        /*
         * This function will save individual FDA repors
         */

        public function actionSaveReport($estid) {
                $model_report = $this->GenerateInvoiceNo($estid);
                Yii::$app->SetValues->Attributes($model_report);
                if ($model_report->save()) {
                        $estimate_ids = explode("_", $estid);
                        foreach ($estimate_ids as $value) {
                                $close_estimate = CloseEstimate::findOne($value);
                                $close_estimate->status = 1;
                                $close_estimate->save();
                        }
                        echo "<script>window.close();</script>";
                        exit;
                }
        }

        /*
         * This function generate invoice number for individual report
         * This function is called from 'fda_report' view and 'SaveReport' controller action
         * return model
         */

        public function GenerateInvoiceNo($estid) {
                $estimate = explode("_", $estid);
                $model_report = new InvoiceNumber();
                $close_estimate = CloseEstimate::findOne($estimate[0]);
                $arr1 = ['1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L'];
                $last = InvoiceNumber::find()->orderBy(['id' => SORT_DESC])->where(['invoice_type' => $close_estimate->invoice_type])->one();
                $last_report_saved = InvoiceNumber::find()->orderBy(['id' => SORT_DESC])->where(['appointment_id' => $close_estimate->apponitment_id, 'invoice_type' => $close_estimate->invoice_type])->one();
                $model_report->appointment_id = $close_estimate->apponitment_id;
                $model_report->invoice_type = $close_estimate->invoice_type;
                $model_report->estimate_id = implode(",", $estimate);
                $model_report->report = Yii::$app->session['fda'];
                if (!empty($last)) {
                        if (empty($last_report_saved)) {
                                $model_report->invoice_number = $last->invoice_number + 1;
                        } else {
                                $model_report->invoice_number = $last_report_saved->invoice_number;
                        }
                } else {
                        if ($close_estimate->invoice_type == 1) {
                                $model_report->invoice_number = 85;
                        } elseif ($close_estimate->invoice_type == 3) {
                                $model_report->invoice_number = 87;
                        } elseif ($close_estimate->invoice_type == 7) {
                                $model_report->invoice_number = 91;
                        } elseif ($close_estimate->invoice_type == 8) {
                                $model_report->invoice_number = 48;
                        } else {
                                return;
                        }
                }
                $sub_invoice_saved = InvoiceNumber::find()->orderBy(['id' => SORT_DESC])->where(['appointment_id' => $close_estimate->apponitment_id, 'invoice_type' => $close_estimate->invoice_type])->all();
                $key = count($sub_invoice_saved);
                if ($key == 0) {
                        $model_report->sub_invoice = $model_report->invoice_number;
                } else {
                        $model_report->sub_invoice = $model_report->invoice_number . $arr1[$key];
                }
                return $model_report;
        }

        /*
         * This function shows saved individual reports
         */

        public function actionShowReport($id) {
                $model_report = InvoiceNumber::findOne($id);
                $model_report->report;
                return $this->renderPartial('_old', [
                            'model_report' => $model_report,
                ]);
        }

        /*
         * This function remove saved report from Invoice Number Table
         */

        public function actionRemoveReport($id, $est_id) {
                if (Yii::$app->session['post']['id'] == 1) {
                        InvoiceNumber::findOne($id)->delete();
                        $estimate_ids = explode(",", $est_id);
                        foreach ($estimate_ids as $value) {
                                $close_estimate = CloseEstimate::findOne($value);
                                $close_estimate->status = 0;
                                $close_estimate->UB = Yii::$app->user->identity->id;
                                $close_estimate->save();
                        }
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        /*
         * This function will edit the close estimate text field on double click
         * and also save changes to the database
         */

        public function actionEditEstimate() {
                if (Yii::$app->request->isAjax) {
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $value = $_POST['valuee'];
                        $estimate = CloseEstimate::find()->where(['id' => $id])->one();
                        if ($name == 'unit' || $name == 'unit_rate') {
                                if ($name == 'unit') {
                                        $estimate->fda = $estimate->unit_rate * $value;
                                } else {
                                        $estimate->fda = $estimate->unit * $value;
                                }
                        }
                        if ($value != '') {
                                $estimate->$name = $value;
                                $estimate->UB = Yii::$app->user->identity->id;
                                if ($estimate->save()) {
                                        return 1;
                                } else {
                                        return 2;
                                }
                        }
                }
        }

        /*
         * This function will edit the close estimate dropdown field on double click
         * and also save changes to the database
         */

        public function actionEditEstimateService() {
                if (Yii::$app->request->isAjax) {
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $value = $_POST['valuee'];
                        $estimate = CloseEstimate::find()->where(['id' => $id])->one();
                        if ($value != '') {
                                $estimate->$name = $value;
                                $estimate->UB = Yii::$app->user->identity->id;
                                if ($estimate->save()) {
                                        if ($name == 'service_id') {
                                                $servicess = Services::find()->where(['id' => $value])->one();
                                                $options = "<option value='" . $servicess->id . "'>" . $servicess->service . "</option>";
                                        } elseif ($name == 'invoice_type') {
                                                $invoice_type = InvoiceType::find()->where(['id' => $value])->one();
                                                $options = "<option value='" . $invoice_type->id . "'>" . $invoice_type->invoice_type . "</option>";
                                        } elseif ($name == 'payment_type') {
                                                if ($value == 1) {
                                                        $options = "<option value='" . $value . "'>" . 'Manual' . "</option>";
                                                } else {
                                                        $options = "<option value='" . $value . "'>" . 'Check' . "</option>";
                                                }
                                        } elseif ($name == 'principal') {
                                                $principals = Debtor::find()->where(['id' => $value])->one();
                                                $options = "<option value='" . $principals->id . "'>" . $principals->principal_id . "</option>";
                                        }
                                        echo $options;
                                }
                        }
                }
        }

        /*
         * This function first check the close estimae has completed or not
         * If complete it will change the appointment status
         * After closing cnnot edit the close estimate
         */

        public function actionEstimateComplete($id) {
                $appointment = Appointment::findOne($id);
                $estimates = CloseEstimate::findAll(['apponitment_id' => $id]);
                if (!empty($estimates)) {
                        if (Yii::$app->session['post']['id'] == 1) {
                                $appointment->stage = 5;
                                $appointment->sub_stages = 5;
                                $appointment->status = 0;
                                $appointment->UB = Yii::$app->user->identity->id;
                                $appointment->save();
                                return $this->redirect(['add', 'id' => $id]);
                        } else {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                } else {
                        Yii::$app->getSession()->setFlash('error', 'Close Estimate Not Completed..');
                        return $this->redirect(['add', 'id' => $id]);
                }
        }

}
