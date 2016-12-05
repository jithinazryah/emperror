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
                $actual_fundings = ActualFunding::findAll(['appointment_id' => $id]);
                if (empty($model)) {
                        $supplier_fundings = $this->SetData($actual_fundings, $id);
                }
                $supplier_fundings = SupplierFunding::findAll(['appointment_id' => $id]);
                return $this->render('add', [
                            'model' => $model,
                            'supplier_fundings' => $supplier_fundings,
                            'appointment' => $appointment,
                            'id' => $id,
                ]);
        }

        protected function SetData($actual_fundings, $id) {

                foreach ($actual_fundings as $actual_funding) {
                        $model = new SupplierFunding;
                        $model->appointment_id = $id;
                        $model->close_estimate_id = $actual_funding->close_estimate_id;
                        $model->service_id = $actual_funding->service_id;
                        $model->supplier = $actual_funding->supplier;
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
                $supplier_model = SupplierFunding::findOne(['id' => $key]);
                $supplier_model->amount_debit = $value;
                $supplier_model->balance_amount = abs($supplier_model->actual_amount - $value);
                $supplier_model->save(false);
                return TRUE;
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
