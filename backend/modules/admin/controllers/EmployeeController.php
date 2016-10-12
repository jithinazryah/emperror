<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Employee;
use common\models\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller {

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
         * Lists all Employee models.
         * @return mixed
         */
        public function actionIndex() {
                $searchModel = new EmployeeSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        /**
         * Displays a single Employee model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new Employee model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate() {
                $model = new Employee();
                $model->setScenario('create');

                if ($model->load(Yii::$app->request->post())) {
                        if ($model->validate() && $this->branch($model, $_POST['Employee']['branch_id']) && $model->save() && $this->upload($model)) {
                                return $this->redirect(['view', 'id' => $model->id]);
                        }
                }
                return $this->render('create', [
                            'model' => $model,
                ]);
        }

        /**
         * Updates an existing Employee model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                $model = $this->findModel($id);

                if ($model->load(Yii::$app->request->post()) && $this->branch($model, $_POST['Employee']['branch_id']) && $this->upload($model) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                }
        }

        /**
         * Deletes an existing Employee model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /**
         * Finds the Employee model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return Employee the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = Employee::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        public function Upload($model) {
                $model->photo = UploadedFile::getInstance($model, 'photo');
                if (isset($model->photo)) {
                        if ($model->photo->saveAs(Yii::$app->basePath . '/web/uploads/' . $model->id . '.' . $model->photo->extension)) {
                                if ($model->isNewRecord) {
                                        $update = Employee::findOne($model->id);
                                        $update->photo = $model->id . '.' . $model->photo->extension;
                                        $update->update();
                                } else {
                                        $model->photo = $model->id . '.' . $model->photo->extension;
                                }
                                return true;
                        } else {
                                return false;
                        }
                } else {
                        if (!$model->isNewRecord) {
                                $update = Employee::findOne($model->id);
                                $model->photo = $update->photo;
                        }
                        return true;
                }
        }

        public function Branch($model, $branch) {

                if ($model != null && $branch != '') {
                        $model->branch_id = implode(",", $branch);
                        Yii::$app->SetValues->Attributes($model);
                        if ($model->isNewRecord):
                                $model->password = Yii::$app->security->generatePasswordHash($model->password);
                        endif;
                        return true;
                }else {
                        return false;
                }
        }

}
