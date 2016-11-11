<?php

namespace backend\modules\masters\controllers;

use Yii;
use common\models\Uploads;
use common\models\UploadsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UploadsController implements the CRUD actions for Uploads model.
 */
class UploadsController extends Controller {

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
         * Lists all Uploads models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new UploadsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single Uploads model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new Uploads model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new Uploads();
                $model->setScenario('create');
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $files = UploadedFile::getInstance($model, 'upload_file');
                        $model->upload_file = $files->name;
                        $model->save();
                        $root_path = 'uploads/common_uploads/' . $model->id;
                        if (!empty($files)) {
                                Yii::$app->UploadFile->UploadSingle($files, $model,$root_path);
                        }
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Updates an existing Uploads model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);
                // $model->setScenario('update');
                $path = 'uploads/common_uploads/' . $model->id . '/' . $model->upload_file;
                if ($model->load(Yii::$app->request->post()) && Yii::$app->SetValues->Attributes($model)) {
                        $files = UploadedFile::getInstance($model, 'upload_file');
                        if (empty($files)) {
                                $data = Uploads::findOne($id);
                                $model->upload_file = $data->upload_file;
                        } else {
                                $model->upload_file = $files->name;
                                if (file_exists($path)) {
                                        unlink($path);
                                }
                                $root_path = 'uploads/common_uploads/' . $model->id;
                                Yii::$app->UploadFile->UploadSingle($files, $model,$root_path);
                        }
                        $model->save();
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing Uploads model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the Uploads model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return Uploads the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = Uploads::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

}
