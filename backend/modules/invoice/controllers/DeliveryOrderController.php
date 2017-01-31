<?php

namespace backend\modules\invoice\controllers;

use Yii;
use common\models\DeliveryOrder;
use common\models\DeliveryOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Orders;

/**
 * DeliveryOrderController implements the CRUD actions for DeliveryOrder model.
 */
class DeliveryOrderController extends Controller {

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
         * Lists all DeliveryOrder models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new DeliveryOrderSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single DeliveryOrder model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new DeliveryOrder model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new DeliveryOrder();

                if ($model->load(Yii::$app->request->post())) {
                        Yii::$app->SetValues->Attributes($model);
                        $model->ref_no = 'DO' . rand(0, 100000) . '/' . date('Y');
                        $model->date = date('Y-m-d');
                        if ($model->save())
                                return $this->redirect(['/invoice/delivery-order/add', 'id' => $model->id]);
                        else {
                                return $this->render('create', [
                                            'model' => $model,
                                ]);
                        }
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        public function actionAdd($id, $invoice_details_id = NULL) {
                $order_details = Orders::findAll(['order_id' => $id]);
                if (!isset($invoice_details_id)) {
                        $model = new Orders();
                } else {
                        $model = Orders::find()->where(['id' => $invoice_details_id])->one();
                }
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $model->order_id = $id;
                        if ($model->save()) {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                }

                return $this->render('add', [
                            'model' => $model,
                            'order_details' => $order_details,
                            'id' => $id,
                ]);
        }

        /**
         * Updates an existing DeliveryOrder model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);

                if ($model->load(Yii::$app->request->post())) {
                        Yii::$app->SetValues->Attributes($model);
                        $model->save();
                        return $this->redirect(['update', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing DeliveryOrder model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the DeliveryOrder model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return DeliveryOrder the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = DeliveryOrder::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        public function actionReports($id) {
                $order = DeliveryOrder::find()->where(['id' => $id])->one();
                $order_details = Orders::findAll(['order_id' => $id]);
                echo $this->renderPartial('report', [
                    'order' => $order,
                    'order_details' => $order_details,
                    'id' => $id,
                ]);
                exit;
        }

        public function actionDeleteInvoice($id) {
                $invoice = Orders::findOne(['id' => $id]);
                $invoice->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

}
