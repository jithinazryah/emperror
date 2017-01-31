<?php

namespace backend\modules\invoice\controllers;

use Yii;
use common\models\GenerateInvoice;
use common\models\GenerateInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\InvoiceGenerateDetails;

/**
 * GenerateInvoiceController implements the CRUD actions for GenerateInvoice model.
 */
class GenerateInvoiceController extends Controller {

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
         * Lists all GenerateInvoice models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new GenerateInvoiceSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single GenerateInvoice model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new GenerateInvoice model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new GenerateInvoice();
                if ($model->load(Yii::$app->request->post())) {
                        Yii::$app->SetValues->Attributes($model);
                        $last_invoice = GenerateInvoice::find()->orderBy(['id' => SORT_DESC])->where(['status' => 1])->one();
                        $last = $last_invoice->id + 1;
                        $model->invoice_number = 'GI/' . date('M' . '/' . date('Y') . '/' . $last);
                        $model->date = date('Y-m-d');
                        $model->oops_id = 'app';
                        $doc_start = $this->GenerateDoc($model->on_account_of);
                        $model->doc_no = $doc_start . $last;
                        if ($model->save())
                                return $this->redirect(['/invoice/generate-invoice/add', 'id' => $model->id]);
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

        public function GenerateDoc($data) {
                if ($data == 1) {
                        $doc_start = 'CG';
                } elseif ($data == 2) {
                        $doc_start = 'CC';
                } elseif ($data == 3) {
                        $doc_start = 'EH';
                } elseif ($data == 4) {
                        $doc_start = 'TC';
                }
                return $doc_start;
        }

        public function actionAdd($id, $invoice_details_id = NULL) {
                $invoice_details = InvoiceGenerateDetails::findAll(['invoice_id' => $id]);
                if (!isset($invoice_details_id)) {
                        $model = new InvoiceGenerateDetails();
                } else {
                        $model = InvoiceGenerateDetails::find()->where(['id' => $invoice_details_id])->one();
                }
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $model->total = $model->qty * $model->unit_price;
                        $model->invoice_id = $id;
                        if ($model->save()) {
                                return $this->redirect(['add', 'id' => $id]);
                        }
                }

                return $this->render('add', [
                            'model' => $model,
                            'invoice_details' => $invoice_details,
                            'id' => $id,
                ]);
        }

        /**
         * Updates an existing GenerateInvoice model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);

                if ($model->load(Yii::$app->request->post())) {
                        $doc_start = $this->GenerateDoc($model->on_account_of);
                        $model->doc_no = $doc_start . $id;
                        $model->save();
                        return $this->redirect(['update', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing GenerateInvoice model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the GenerateInvoice model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return GenerateInvoice the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = GenerateInvoice::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        public function actionInvoiceAddress() {
                if (Yii::$app->request->isAjax) {
                        $invoice_id = $_POST['invoice_id'];
                        $invoice_address = \common\models\Debtor::find()->where(['id' => $invoice_id])->one();
                        return $invoice_address->invoicing_address;
                }
        }

        public function actionReports($id) {
                $invoice = GenerateInvoice::find()->where(['id' => $id])->one();
                $invoice_details = InvoiceGenerateDetails::findAll(['invoice_id' => $id]);
                echo $this->renderPartial('report', [
                    'invoice' => $invoice,
                    'invoice_details' => $invoice_details,
                    'id' => $id,
                ]);
                exit;
        }

        public function actionDeleteInvoice($id) {
                $invoice = InvoiceGenerateDetails::findOne(['id' => $id]);
                $invoice->delete();
                return $this->redirect(Yii::$app->request->referrer);
        }

}
