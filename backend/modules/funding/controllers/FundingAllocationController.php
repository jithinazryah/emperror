<?php

namespace backend\modules\funding\controllers;

use Yii;
use common\models\FundingAllocation;
use common\models\FundingAllocationSearch;
use common\models\Appointment;
use common\models\OnAccount;
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
                        $model->fund_date = Yii::$app->ChangeDateFormate->SingleDateFormat($model->fund_date);
                }
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $check = $_POST['check'];
                        $model->appointment_id = $id;
                        $model->type = 1;
                        $model->fund_date = Yii::$app->ChangeDateFormate->SingleDateFormat($model->fund_date);
                        if ($check == 1) {
                                $onaccount = OnAccount::find()->orderBy(['id' => SORT_DESC])->where(['debtor_id' => $model->principal_id])->one();
                                if (!empty($onaccount)) {
                                        if ($onaccount->balance >= $model->amount) {
                                                $model->save(false);
                                                $this->UpdateOnAccount($model, $onaccount);
                                        } else {
                                                Yii::$app->getSession()->setFlash('error', 'On Account amount is Lower');
                                                return $this->redirect(['add', 'id' => $id]);
                                        }
                                }
                        } else {
                                $model->save(false);
                        }
                        return $this->redirect(['add', 'id' => $id]);
                }
                return $this->render('add', [
                            'model' => $model,
                            'fundings' => $fundings,
                            'appointment' => $appointment,
                            'id' => $id,
                ]);
        }

        public function UpdateOnAccount($model_fund, $onaccount) {
                $model = new OnAccount;
                $model->debtor_id = $model_fund->principal_id;
                $model->transaction_type = 2;
                $model->payment_type = $model_fund->principal_id;
                $model->check_no = $model_fund->check_no;
                $model->amount = $model_fund->amount;
                $model->date = $model_fund->fund_date;
                $model->appointment_id = $model_fund->appointment_id;
                $model->balance = $onaccount->balance - $model_fund->amount;
                $model->comment = $model_fund->description;
                $model->date = Yii::$app->ChangeDateFormate->SingleDateFormat($model->date);
                Yii::$app->SetValues->Attributes($model);
                $model->save();
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
