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
                $estimates = EstimatedProforma::findAll(['apponitment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $model_upload = new UploadFile();
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

        public function actionUploads() {
                $model_upload = new UploadFile();
                if ($model_upload->load(Yii::$app->request->post())) {
                        $files = UploadedFile::getInstances($model_upload, 'filee');
                        if (Yii::$app->UploadFile->Upload($files, $model_upload)) {

                                return $this->redirect(Yii::$app->request->referrer);
                        }
                }
        }

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

        public function actionDeletePerforma($id) {
                $this->findModel($id)->delete();

                //return $this->redirect(['index']);
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
//                        $appointment->stage = 2;
//                        $appointment->sub_stages = 2;
                        $appointment->save();
                        return $this->redirect(['/appointment/port-call-data/update', 'id' => $appointment->id]);
                } else {
                        Yii::$app->getSession()->setFlash('error', 'Estimated Proforma Not Completed..');
                        return $this->redirect(['add', 'id' => $id]);
                }
        }

        protected function UpdateFundAllocation($id, $appointment) {
                $principp = explode(',', $appointment->principal);
                foreach ($principp as $value) {
                        $estimates = EstimatedProforma::findAll(['apponitment_id' => $id, 'principal' => $value]);
                        $epda_total = 0;
                        foreach ($estimates as $estimate) {
                                $epda_total += $estimate->epda;
                        }
                        $model_fund = new FundingAllocation;
                        $model_fund->appointment_id = $id;
                        $model_fund->fund_date = date('Y-m-d h:m:s');
                        $model_fund->outstanding = $epda_total;
                        $model_fund->type = 'EPDA';
                        $model_fund->principal_id = $value;
                        $model_fund->save(false);
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

        protected function SetData($performa_check, $id) {

                foreach ($performa_check as $pfrma) {
                        $value = EstimatedProforma::find()->where(['id' => $pfrma->id])->one();
                        $model = new EstimatedProforma;
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
                        $model->status = $value->status;
                        $model->CB = Yii::$app->user->identity->id;
                        $model->DOC = date('Y-m-d');
                        $model->save();
                }
                return TRUE;
        }

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

        public function actionReports() {
                $princip = $_POST['principal'];
//                if($princip == 'Select Principal'){
//                        return;
//                }
                $app = $_POST['app_id'];
                //$estimates = EstimatedProforma::findAll(['apponitment_id' => $app, 'principal' => $princip]);
                // get your HTML raw content without any layouts or scripts
                $appointment = Appointment::findOne($app);

                //var_dump($appointment);exit;
                Yii::$app->session->set('epda', $this->renderPartial('report', [
                            'appointment' => $appointment,
                            //'estimates' => $estimates,
                            'princip' => $princip,
                ]));

                echo Yii::$app->session['epda'];
                exit;

                // setup kartik\mpdf\Pdf component
                /*  $pdf = new Pdf([
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
                /* ]);

                  // return the pdf output as per the destination setting
                  return $pdf->render(); */
        }

        public function actionRemove($path) {
                unlink($path);
                return $this->redirect(Yii::$app->request->referrer);
        }

        public function actionSaveReport($id) {
                $model_report = new EstimateReport();
                $model_report->appointment_id = $id;
                $model_report->report = Yii::$app->session['epda'];
                $model_report->status = 1;
                $model_report->save();
//                return $this->redirect(Yii::$app->request->referrer);

                echo "<script>window.close();</script>";
                exit;
        }

        public function actionShowReport($id) {
                $model_report = EstimateReport::findOne($id);
                $model_report->report;
                return $this->renderPartial('_old', [
                            'model_report' => $model_report,
                ]);
        }

        public function actionRemoveReport($id) {
                EstimateReport::findOne($id)->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

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
