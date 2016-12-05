<?php

namespace backend\modules\funding\controllers;

use Yii;
use common\models\ActualFunding;
use common\models\ActualFundingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Appointment;
use common\models\CloseEstimate;

/**
 * ActualFundingController implements the CRUD actions for ActualFunding model.
 */
class ActualFundingController extends Controller {

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
         * Lists all ActualFunding models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new ActualFundingSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single ActualFunding model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        public function actionAdd($id, $fund_id = NULL) {
                $model = ActualFunding::findAll(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);
                $close_estimates = CloseEstimate::findAll(['apponitment_id' => $id]);
                if (empty($model)) {
                        $actual_fundings = $this->SetData($close_estimates, $id);
                }

//                if (!isset($fund_id)) {
//                        $model = new ActualFunding;
//                } else {
//                        $model = $this->findModel($fund_id);
//                }
//
//                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
//                        $model->amount_difference = abs($model->fda_amount - $model->actual_amount);
//                        if ($model->save(false)) {
//                                return $this->redirect(['add', 'id' => $id]);
//                        }
//                }
                $actual_fundings = ActualFunding::findAll(['appointment_id' => $id]);
                return $this->render('add', [
                            'model' => $model,
                            'actual_fundings' => $actual_fundings,
                            'appointment' => $appointment,
                            'id' => $id,
                ]);
        }

        protected function SetData($close_estimates, $id) {

                foreach ($close_estimates as $close_estimate) {
                        $model = new ActualFunding;
                        $model->appointment_id = $id;
                        $model->close_estimate_id = $close_estimate->id;
                        $model->service_id = $close_estimate->service_id;
                        $model->supplier = $close_estimate->supplier;
                        $model->unit_rate = $close_estimate->unit_rate;
                        $model->unit = $close_estimate->unit;
                        $model->fda_amount = $close_estimate->fda;
                        $model->status = 1;
                        Yii::$app->SetValues->Attributes($model);
                        $model->save();
                }
                return $model;
        }

        public function actionSaveActualPrice() {
                $id = $_POST['app_id'];
                if (isset($_POST['actual_amount']) && $_POST['actual_amount'] != '') {
                        foreach ($_POST['actual_amount'] as $key => $value) {
                                $this->UpdateActualPrice($key, $value);
                        }
                        return $this->redirect(['add', 'id' => $id]);
                }
        }

        protected function UpdateActualPrice($key, $value) {
                $actual_model = ActualFunding::findOne(['id' => $key]);
                $actual_model->actual_amount = $value;
                $actual_model->amount_difference = abs($actual_model->fda_amount - $value);
                $actual_model->save(false);
                return TRUE;
        }

        /**
         * Creates a new ActualFunding model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new ActualFunding();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing ActualFunding model.
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
         * Deletes an existing ActualFunding model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the ActualFunding model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return ActualFunding the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = ActualFunding::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

}
