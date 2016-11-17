<?php

namespace backend\modules\funding\controllers;

use Yii;
use common\models\FundingAllocation;
use common\models\FundingAllocationSearch;
use common\models\Appointment;
use common\models\AppointmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FundingAllocationController implements the CRUD actions for FundingAllocation model.
 */
class FundingAllocationController extends Controller {

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
         * Lists all FundingAllocation models.
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
         * Displays a single FundingAllocation model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new FundingAllocation model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new FundingAllocation();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing FundingAllocation model.
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

        public function actionAdd($id, $fund_id = NULL) {
                $fundings = FundingAllocation::findAll(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);

                if (!isset($fund_id)) {
                        $model = new FundingAllocation;
                } else {
                        $model = $this->findModel($fund_id);
                }

                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $model->appointment_id = $id;
                        if ($model->save()) {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                        // return $this->refresh();
                }
                return $this->render('add', [
                            'model' => $model,
                            'fundings' => $fundings,
                            'appointment' => $appointment,
                            'id' => $id,
                ]);
        }

        public function actionDeleteFund($id) {

                $this->findModel($id)->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

        /**
         * Deletes an existing FundingAllocation model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the FundingAllocation model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return FundingAllocation the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = FundingAllocation::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

}
