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


/**
 * CloseEstimateController implements the CRUD actions for CloseEstimate model.
 */
class CloseEstimateController extends Controller {

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
                        $model->epda = $model->unit_rate * $model->unit;
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
                                        $this->AddSubService($estimate_sub_services,$model->id,$id);
                                }
                        }
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

        public function AddSubService($estimate_sub_services,$id,$appointment_id){
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
                if ($invoice_type == 'all') {
                        $princip = CloseEstimate::findAll(['principal' => $principp, 'apponitment_id' => $app]);
                } else {
                        if ($principp != '') {
                                $princip = CloseEstimate::findOne(['invoice_type' => $invoice_type, 'apponitment_id' => $app, 'principal' => $principp]);
                        } else {
                                $princip = CloseEstimate::findOne(['invoice_type' => $invoice_type, 'apponitment_id' => $app]);
                        }
                }
                // get your HTML raw content without any layouts or scripts
                $appointment = Appointment::findOne($app);
                $ports = PortCallData::findOne($app);
                //var_dump($appointment);exit;
                echo $content = $this->renderPartial('report', [
            'appointment' => $appointment,
            'invoice_type' => $invoice_type,
            'princip' => $princip,
            'ports' => $ports,
            'principp' => $principp,
                ]);
                exit;

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

        public function actionRemove($path) {
                unlink($path);
                return $this->redirect(Yii::$app->request->referrer);
        }

}
