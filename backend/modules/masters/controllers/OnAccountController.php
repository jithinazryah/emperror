<?php

namespace backend\modules\masters\controllers;

use Yii;
use common\models\OnAccount;
use common\models\OnAccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OnAccountController implements the CRUD actions for OnAccount model.
 */
class OnAccountController extends Controller {

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
         * Lists all OnAccount models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new OnAccountSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single OnAccount model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new OnAccount model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new OnAccount();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing OnAccount model.
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
         * Deletes an existing OnAccount model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the OnAccount model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return OnAccount the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = OnAccount::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        public function actionAdd($id, $fund_id = NULL) {
                $on_accounts = OnAccount::findAll(['debtor_id' => $id]);
                if (!isset($fund_id)) {
                        $model = new OnAccount;
                } else {
                        $model = $this->findModel($fund_id);
                        $model->date = Yii::$app->ChangeDateFormate->SingleDateFormat($model->date);
                }

                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $onaccount = OnAccount::find()->orderBy(['id' => SORT_DESC])->where(['debtor_id' => $id])->one();
                        $model->debtor_id = $id;
                        $model->transaction_type = 1;
                        $model->balance = $onaccount->balance + $model->amount;
                        $model->date = Yii::$app->ChangeDateFormate->SingleDateFormat($model->date);

                        if ($model->save(false)) {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                }
                return $this->render('add', [
                            'model' => $model,
                            'on_accounts' => $on_accounts,
                            'id' => $id,
                ]);
        }

}
