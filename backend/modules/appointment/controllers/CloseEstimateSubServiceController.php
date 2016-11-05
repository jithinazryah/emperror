<?php

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\CloseEstimateSubService;
use common\models\CloseEstimateSubServiceSearch;
use common\models\CloseEstimate;
use common\models\Appointment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CloseEstimateSubServiceController implements the CRUD actions for CloseEstimateSubService model.
 */
class CloseEstimateSubServiceController extends Controller {

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
         * Lists all CloseEstimateSubService models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new CloseEstimateSubServiceSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single CloseEstimateSubService model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        public function actionAdd($id, $sub_id = NULL) {
                $estimates = CloseEstimate::findOne(['id' => $id]);
                $appointment = Appointment::findOne($estimates->apponitment_id);
                $subcat = CloseEstimateSubService::findAll(['close_estimate_id' => $id]);
                $subtotal = 0;
                if (!empty($subcat)) {
                        foreach ($subcat as $sub) {
                                $subtotal += $sub->total;
                        }
                        $estimates = CloseEstimate::findOne(['id' => $sub->close_estimate_id]);
                        $estimates->epda = $subtotal;
                        $estimates->fda = $subtotal;
                        $estimates->save();
                }
                if (!isset($sub_id)) {
                        $model = new CloseEstimateSubService();
                } else {
                        $model = $this->findModel($sub_id);
                }

                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model, $id)) {
                        $model->total = $model->unit * $model->unit_price;
                        $model->service_id = $estimates->apponitment_id;
                        $model->appointment_id = $estimates->apponitment_id;
                        $model->close_estimate_id = $id;
                        if ($model->save()) {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                }
                return $this->render('add', [
                            'model' => $model,
                            'appointment' => $appointment,
                            'subcat' => $subcat,
                            'estimates' => $estimates,
                ]);
        }
        
        public function actionDeleteSub($id) {
                $this->findModel($id)->delete();

                //return $this->redirect(['index']); 
                return $this->redirect(Yii::$app->request->referrer);
        }

        /**
         * Creates a new CloseEstimateSubService model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new CloseEstimateSubService();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing CloseEstimateSubService model.
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
         * Deletes an existing CloseEstimateSubService model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the CloseEstimateSubService model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return CloseEstimateSubService the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = CloseEstimateSubService::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

}
