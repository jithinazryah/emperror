<?php

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\Test;
use common\models\TestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestController implements the CRUD actions for Test model.
 */
class TestController extends Controller {

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
         * Lists all Test models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new TestSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single Test model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new Test model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new Test();

                if ($model->load(Yii::$app->request->post())) {
                        $this->dateformat($model, $_POST['Test']);
                        $model->save();
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing Test model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post())) {
                        $this->dateformat($model);
                        
                        $model->save();
                        $model = $this->dateformat($model);

                        return $this->redirect(['update', 'id' => $model->id]);
                } else {
                        $model = $this->dateformat($model);
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        public function DateFormat($model) {
                if (!empty($model)) {
                        $a = ['additional_info', 'comments', 'status'];
                        foreach ($model->attributes as $key => $dta) {
                                if (!in_array($key, $a)) {
                                        if (strpos($dta, '-') == false) {
                                                
                                                if (strlen($dta) < 16 && strlen($dta) >= 8 && $dta != NULL)
                                                        $model->$key = $this->ChangeFormat($dta);
                                                //echo $model->$key;exit;
                                        }else {
                                                $year = substr($dta, 0, 4);
                                                $month = substr($dta, 5, 2);
                                                $day = substr($dta, 8, 2);
                                                $hour = substr($dta, 11, 2) == '' ? '00' : substr($dta, 11, 2);
                                                $min = substr($dta, 14, 2) == '' ? '00' : substr($dta, 14, 2);
                                                $sec = substr($dta, 17, 2) == '' ? '00' : substr($dta, 17, 2);

                                                if ($hour != '00' && $min != '00' && $sec != '00') {
                                                        $model->$key = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
                                                } elseif ($hour != '00' && $min != '00') {
                                                        $model->$key = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                                                } elseif ($hour != '00') {
                                                        $model->$key = $year . '-' . $month . '-' . $day . ' ' . $hour . ':00';
                                                } else {
                                                        $model->$key = $year . '-' . $month . '-' . $day;
                                                }
                                        }
                                }
                        }
                        return $model;
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

        /**
         * Deletes an existing Test model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the Test model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return Test the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = Test::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

}
