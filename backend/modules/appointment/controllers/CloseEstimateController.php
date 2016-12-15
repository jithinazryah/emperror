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

                if (Yii::$app->session['post']['admin'] != 1)
                        $this->redirect(['/site/home']);
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
                $estimates = CloseEstimate::findAll(['apponitment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $model_upload = new UploadFile();
//                if (empty($estimates)) {
//                        $this->InsertCloseEstimate($id);
//                        $estimates = CloseEstimate::findAll(['apponitment_id' => $id]);
//                }
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
                        // return $this->refresh();
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
                        $model->status = $value->status;
                        $model->save(false);
                }
                return true;
        }

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

        protected function SetValues($model, $id) {

                if (Yii::$app->SetValues->Attributes($model)) {
                        $model->setAttribute('apponitment_id', $id);
                        return true;
                } else {
                        return false;
                }
        }

        public function actionSupplier() {
                if (Yii::$app->request->isAjax) {
                        $service_id = $_POST['service_id'];
                        $services_data = \common\models\Services::find()->where(['id' => $service_id])->one();
                        echo $services_data->supplier_options;
                }
        }

        /*
         * Function for Multiple File Upload
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
         */

        public function actionReport() {
                empty(Yii::$app->session['fda-report']);
                $invoice_type = $_POST['invoice_type'];
                $app = $_POST['app_id'];
                $principp = $_POST['fda'];
                $invoice_date = $this->SingleDateFormat($_POST['invoice_date']);
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
//                echo Yii::$app->session['fda-report'];
                exit;
        }

        public function actionSaveAllReport($appintment_id, $principal_id) {
                echo 'hii';
                exit;
                $model_report = $this->InvoiceGeneration($appintment_id, $principal_id);
                if ($model_report->save(false)) {
                        $this->UpdateFundAllocation($appintment_id, $principal_id);
                        echo "<script>window.close();</script>";
                        exit;
                }
        }

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
                                $model_report->sub_invoice = $last_report_saved->sub_invoice + 1;
                        } else {
                                $model_report->sub_invoice = $last_report_saved->sub_invoice;
                        }
                }
                return $model_report;
        }

        /*
         * Update Funding allocation when generating final DA
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
                unlink($path);
                return $this->redirect(Yii::$app->request->referrer);
        }

        public function actionFdaReport($id, $estid) {
                // get your HTML raw content without any layouts or scripts
                $close_estimate = CloseEstimate::findOne($estid);
                $appointment = Appointment::findOne($id);
                $ports = PortCallData::findOne(['appointment_id' => $id]);
//                $this->SaveReport($id, $close_estimate->invoice_type, $estid);
                //var_dump($appointment);exit;
                Yii::$app->session->set('fda', $this->renderPartial('fda_report', [
                            'appointment' => $appointment,
                            'close_estimate' => $close_estimate,
                            'save' => false,
                            'print' => true,
                ]));
                echo $this->renderPartial('fda_report', [
                    'appointment' => $appointment,
                    'close_estimate' => $close_estimate,
                    'save' => true,
                    'print' => false,
                ]);

//                echo Yii::$app->session['fda'];
                exit;
        }

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
                                $princip = CloseEstimate::findOne(['invoice_type' => $invoice[0], 'apponitment_id' => $appointment_id]);
                                $close_estimates = CloseEstimate::findAll(['invoice_type' => $invoice[0], 'apponitment_id' => $appointment_id, 'id' => $est_id]);
                                if (!empty($close_estimates)) {
                                        $flag = 0;
                                        foreach ($close_estimates as $close_estimate) {
                                                if ($close_estimate->status == 1) {
                                                        $flag = 1;
                                                }
                                        }
                                        if ($flag == 1) {
                                                $error = 'Already generate FDA on this esimate';
                                                return $this->renderPartial('error', [
                                                            'error' => $error,
                                                ]);
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

//                echo Yii::$app->session['fda'];
                                exit;
                        } else {
                                $error = 'Choose Same Invoice Type';
                                return $this->renderPartial('error', [
                                            'error' => $error,
                                ]);
//                                Yii::$app->getSession()->setFlash('close-error', 'Choose Same Invoice Type');
//                                return $this->redirect(Yii::$app->request->referrer);
//                                exit;
                        }
                }
                exit;
        }

        public function actionSaveReport($estid) {
                $model_report = $this->GenerateInvoiceNo($estid);
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

        public function actionShowReport($id) {
                $model_report = InvoiceNumber::findOne($id);
                $model_report->report;
                return $this->renderPartial('_old', [
                            'model_report' => $model_report,
                ]);
        }

        public function actionShowAllReport($id) {
                $model_report = FdaReport::findOne($id);
                $model_report->report;
                return $this->renderPartial('_old', [
                            'model_report' => $model_report,
                ]);
        }

        public function actionRemoveReport($id) {
                InvoiceNumber::findOne($id)->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

        public function actionRemoveAllReport($id) {
                FdaReport::findOne($id)->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

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
                        $estimate = CloseEstimate::find()->where(['id' => $id])->one();
                        if ($value != '') {
                                $estimate->$name = $value;
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

}
