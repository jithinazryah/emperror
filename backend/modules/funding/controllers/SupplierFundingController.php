<?php

namespace backend\modules\funding\controllers;

use Yii;
use common\models\SupplierFunding;
use common\models\SupplierFundingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Appointment;
use common\models\ActualFunding;
use common\models\CloseEstimate;

/**
 * SupplierFundingController implements the CRUD actions for SupplierFunding model.
 */
class SupplierFundingController extends Controller {

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
         * Lists all SupplierFunding models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new SupplierFundingSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single SupplierFunding model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new SupplierFunding model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionAdd($id) {
                $model = SupplierFunding::findAll(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $close_estimates = CloseEstimate::findAll(['apponitment_id' => $id]);
                if (empty($model)) {
                        $supplier_fundings = $this->SetData($close_estimates, $id);
                }
                $supplier_fundings = SupplierFunding::findAll(['appointment_id' => $id]);
                return $this->render('add', [
                            'model' => $model,
                            'supplier_fundings' => $supplier_fundings,
                            'close_estimates' => $close_estimates,
                            'appointment' => $appointment,
                            'id' => $id,
                ]);
        }

        protected function SetData($close_estimates, $id) {

                foreach ($close_estimates as $close_estimate) {
                        $model = new SupplierFunding;
                        $model->appointment_id = $id;
                        $model->close_estimate_id = $close_estimate->id;
                        $model->service_id = $close_estimate->service_id;
                        $model->supplier = $close_estimate->supplier;
                        $actual_funding = ActualFunding::findOne(['close_estimate_id' => $close_estimate->id]);
                        $model->actual_amount = $actual_funding->actual_amount;
                        $model->status = 1;
                        Yii::$app->SetValues->Attributes($model);
                        $model->save();
                }
                return $model;
        }

        public function actionSaveSupplierPrice() {
                $id = $_POST['app_id'];
                if (isset($_POST['amount_debit']) && $_POST['amount_debit'] != '') {
                        foreach ($_POST['amount_debit'] as $key => $value) {
                                $this->UpdateSupplierPrice($key, $value);
                        }
                        return $this->redirect(['add', 'id' => $id]);
                }
        }

        protected function UpdateSupplierPrice($key, $value) {
                $supplier_model = SupplierFunding::findOne(['close_estimate_id' => $key]);
                if ($supplier_model->amount_debit == '') {
                        $supplier_model->amount_debit = $value;
//                        $supplier_model->balance_amount = abs($supplier_model->actual_amount - $value);
                        $supplier_model->save(false);
                } else {
                        if ($value != '')
                                $this->AddSupplierPrice($supplier_model, $value);
                }
                return TRUE;
        }

        protected function AddSupplierPrice($supplier_model, $value) {
                $model_supplier = new SupplierFunding;
                $model_supplier->appointment_id = $supplier_model->appointment_id;
                $model_supplier->close_estimate_id = $supplier_model->close_estimate_id;
                $model_supplier->service_id = $supplier_model->service_id;
                $model_supplier->supplier = $supplier_model->supplier;
                $model_supplier->actual_amount = $supplier_model->actual_amount;
                $model_supplier->amount_debit = $value;
                $model_supplier->status = 1;
                Yii::$app->SetValues->Attributes($model_supplier);
                $model_supplier->save(false);
                return true;
        }

        public function actionCreate() {
                $model = new SupplierFunding();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing SupplierFunding model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing SupplierFunding model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the SupplierFunding model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return SupplierFunding the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = SupplierFunding::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

}
