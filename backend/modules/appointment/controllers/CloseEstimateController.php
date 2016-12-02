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
         * Generate Close Estimate Report depends on Invoice Tyype
         */

        public function actionReport() {
                $invoice_type = $_POST['invoice_type'];
                $app = $_POST['app_id'];
                $principp = $_POST['fda'];
                $appointment = Appointment::findOne($app);
                $ports = PortCallData::findOne($app);
                if ($invoice_type == 'all') {
                        $this->UpdateFundAllocation($app, $principp);
                        $princip = CloseEstimate::findAll(['principal' => $principp, 'apponitment_id' => $app]);
                        echo $content = $this->renderPartial('report', [
                    'appointment' => $appointment,
                    'invoice_type' => $invoice_type,
                    'princip' => $princip,
                    'ports' => $ports,
                    'principp' => $principp,
                        ]);
                        exit;
                } else {
                        if ($principp != '') {
                                $princip = CloseEstimate::findOne(['invoice_type' => $invoice_type, 'apponitment_id' => $app, 'principal' => $principp]);
                        } else {
                                $princip = CloseEstimate::findOne(['invoice_type' => $invoice_type, 'apponitment_id' => $app]);
                        }
//                        $this->SaveReport($app, $invoice_type);
                        echo $content = $this->renderPartial('report_fda', [
                    'appointment' => $appointment,
                    'invoice_type' => $invoice_type,
                    'princip' => $princip,
                    'ports' => $ports,
                    'principp' => $principp,
                        ]);
                        exit;
                }
                // get your HTML raw content without any layouts or scripts
                //var_dump($appointment);exit;
                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    //'mode' => Pdf::MODE_CORE,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
//                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
//                    'destination' => Pdf::DEST_BROWSER,
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
                        /*                    'methods' => [
                          'SetHeader' => ['Estimated proforma generated on ' . date("d/m/Y h:m:s")],
                          'SetFooter' => ['|page {PAGENO}'],
                          ] */
                ]);

                // return the pdf output as per the destination setting
                return $pdf->render();
        }

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

        public function actionRemove($path) {
                unlink($path);
                return $this->redirect(Yii::$app->request->referrer);
        }

        public function actionFdaReport($id, $estid) {
                // get your HTML raw content without any layouts or scripts
                $close_estimate = CloseEstimate::findOne($estid);
                $appointment = Appointment::findOne($id);
                $ports = PortCallData::findOne($id);
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

        public function actionSaveReport($estid) {
                $model_report = $this->GenerateInvoiceNo($estid);
                $model_report->save();
                echo "<script>window.close();</script>";
                exit;
        }

        public function GenerateInvoiceNo($estid) {
                $model_report = new InvoiceNumber();
                $close_estimate = CloseEstimate::findOne($estid);
                $last = InvoiceNumber::find()->orderBy(['id' => SORT_DESC])->where(['invoice_type' => $close_estimate->invoice_type])->one();
                $last_report_saved = InvoiceNumber::find()->orderBy(['id' => SORT_DESC])->where(['appointment_id' => $close_estimate->apponitment_id, 'invoice_type' => $close_estimate->invoice_type])->one();
                $model_report->appointment_id = $close_estimate->apponitment_id;
                $model_report->invoice_type = $close_estimate->invoice_type;
                $model_report->estimate_id = $estid;
                $model_report->report = Yii::$app->session['fda'];
                if (!empty($last)) {
                        if (empty($last_report_saved)) {
                                $model_report->invoice_number = $last->invoice_number + 1;
                        } else {
                                $model_report->invoice_number = $last_report_saved->invoice_number;
                        }
                } else {
                        if ($close_estimate->invoice_type == 1) {
                                $model_report->invoice_number = 76;
                        } elseif ($close_estimate->invoice_type == 3) {
                                $model_report->invoice_number = 80;
                        } elseif ($close_estimate->invoice_type == 7) {
                                $model_report->invoice_number = 84;
                        } elseif ($close_estimate->invoice_type == 8) {
                                $model_report->invoice_number = 47;
                        } else {
                                return;
                        }
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

        public function actionRemoveReport($id) {
                InvoiceNumber::findOne($id)->delete();
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

}
