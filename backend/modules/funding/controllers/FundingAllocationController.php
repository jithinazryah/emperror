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
                        $model->fund_date = $this->SingleDateFormat($model->fund_date);
                }

                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $model->appointment_id = $id;
                        $model->type = 1;
                        $model->fund_date = $this->SingleDateFormat($model->fund_date);
                        if ($model->save(false)) {
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
