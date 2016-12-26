<?php

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\PortCallData;
use common\models\PortCallDataDraft;
use common\models\PortCallDataRob;
use common\models\Appointment;
use common\models\PortCallDataAdditional;
use common\models\AppointmentSearch;
use common\models\PortCallDataSearch;
use common\models\PortBreakTimings;
use common\models\PortCargoDetails;
use common\models\UploadFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ImigrationClearance;
use common\models\PortStoppages;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * PortCallDataController implements the CRUD actions for PortCallData model.
 */
class PortCallDataController extends Controller {

        public function init() {
                if (Yii::$app->user->isGuest)
                        $this->redirect(['/site/index']);

                if (Yii::$app->session['post']['port_call_data'] != 1) {
                        Yii::$app->getSession()->setFlash('exception', 'You have no permission to access this page');
                        $this->redirect(['/site/exception']);
                }
        }

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
         * Lists all PortCallData models.
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
         * Displays a single PortCallData model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id) {
                return $this->render('view', [
                            'model' => $this->findModel($id),
                ]);
        }

        /**
         * Creates a new PortCallData model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate($id) {
                $appointment = Appointment::find($id)->one();
                $model = new PortCallData();

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                } else {
                        return $this->render('create', [
                                    'model' => $model,
                                    'appointment' => $appointment,
                        ]);
                }
        }

        /**
         * Updates an existing PortCallData model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id) {
                if (Yii::$app->session['post']['port_call_data'] != 1) {
                        Yii::$app->getSession()->setFlash('exception', 'You have no permission to access this page');
                        $this->redirect(['/site/exception']);
                }
                $model_appointment = Appointment::findOne(['id' => $id]);
                $model = PortCallData::findOne(['appointment_id' => $id]);
                $model_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $model_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $model_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $model_imigration = ImigrationClearance::findOne(['appointment_id' => $id]);
                $model_port_break = PortBreakTimings::findAll(['appointment_id' => $id]);
                $model_port_cargo_details = PortCargoDetails::findOne(['appointment_id' => $id]);
                $model_port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                $model_upload = new UploadFile();
                $model = Yii::$app->ChangeDateFormate->DateFormat($model, $model->attributes);
                $this->AddStages($model, $model_appointment);
                if ($model_port_cargo_details == '')
                        $model_port_cargo_details = new PortCargoDetails;

                if (empty($model_appointment))
                        throw new \yii\web\HttpException(404, 'This Appointment could not be found.Eroor Code:1001');
                $model_add = new PortCallDataAdditional();
                /*
                 * If the data depends on the appointment ID is empty then creating a new entry
                 */
                if ($this->Check($id, $model, $model_draft, $model_rob, $model_imigration)) {
                        $model = PortCallData::findOne(['appointment_id' => $id]);
                        $model_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                        $model_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                        $model_imigration = ImigrationClearance::findOne(['appointment_id' => $id]);
                        $model_port_break = PortBreakTimings::findAll(['appointment_id' => $id]);
                        $model_port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                } else {

                        throw new \yii\web\HttpException(404, 'This Appointment could not be found.Eroor Code:1002');
                }

                if ($model->load(Yii::$app->request->post()) && $model_imigration->load(Yii::$app->request->post())) {
                        $this->saveportcalldata($model, $model_imigration);
                        $model_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                        $this->AddStages($model, $model_appointment);
                } else if ($model_rob->load(Yii::$app->request->post()) && $model_draft->load(Yii::$app->request->post())) {
                        $this->saveportcalldraftrob($model_rob, $model_draft);
                }
                $model = Yii::$app->ChangeDateFormate->DateFormat($model);
                $model_imigration = Yii::$app->ChangeDateFormate->DateFormat($model_imigration);
                $model_draft = Yii::$app->ChangeDateFormate->DateFormat($model_draft);
                foreach ($model_additional as $additional) {
                        $additional->value = Yii::$app->ChangeDateFormate->SingleDateFormat($additional->value);
                }
                foreach ($model_port_stoppages as $port_stoppages) {
                        $port_stoppages->stoppage_from = Yii::$app->ChangeDateFormate->SingleDateFormat($port_stoppages->stoppage_from);
                        $port_stoppages->stoppage_to = Yii::$app->ChangeDateFormate->SingleDateFormat($port_stoppages->stoppage_to);
                }
                return $this->render('update', [
                            'model' => $model,
                            'model_draft' => $model_draft,
                            'model_rob' => $model_rob,
                            'model_add' => $model_add,
                            'model_imigration' => $model_imigration,
                            'model_appointment' => $model_appointment,
                            'model_additional' => $model_additional,
                            'model_port_break' => $model_port_break,
                            'model_port_cargo_details' => $model_port_cargo_details,
                            'model_port_stoppages' => $model_port_stoppages,
                            'model_upload' => $model_upload,
                ]);
        }

        /*
         * This function save port call data
         */

        public function SavePortcallData($model, $model_imigration) {
                Yii::$app->SetValues->Attributes($model);
                Yii::$app->SetValues->Attributes($model_imigration);
                Yii::$app->ChangeDateFormate->DateFormat($model);
                Yii::$app->ChangeDateFormate->DateFormat($model_imigration);
                $model->save();
                $model_imigration->save();
                if (isset($_POST['create']) && $_POST['create'] != '') {
                        $this->AddAditionalCreate($_POST['create'], $model);
                }

                if (isset($_POST['updatee']) && $_POST['updatee'] != '') {
                        $this->AddAditionalUpdate($_POST['updatee']);
                }
                /*
                 * for delete additional data
                 */
                if (isset($_POST['delete_port_vals']) && $_POST['delete_port_vals'] != '') {
                        $this->AddAditionalDelete($_POST['delete_port_vals']);
                }
                return true;
        }

        /*
         * for create additional data
         */

        public function AddAditionalCreate($create, $model) {
                $arr = [];
                $i = 0;
                foreach ($create['label'] as $val) {
                        $arr[$i]['label'] = $val;
                        $i++;
                }
                $i = 0;
                foreach ($create['valuee'] as $val) {
                        $arr[$i]['valuee'] = $val;
                        $i++;
                }
                $i = 0;
                foreach ($create['comment'] as $val) {
                        $arr[$i]['comment'] = $val;
                        $i++;
                }
                $this->SaveAddAitional($arr, $model);
        }

        /*
         * for save add portcall data additional
         */

        public function SaveAddAitional($arr, $model) {
                foreach ($arr as $val) {
                        $aditional = new PortCallDataAdditional;
                        $aditional->appointment_id = $model->appointment_id;
                        $aditional->label = $val['label'];
                        $aditional->value = Yii::$app->ChangeDateFormate->SingleDateFormat($val['valuee']);
                        $aditional->comment = $val['comment'];
                        $aditional->status = 1;
                        $aditional->CB = Yii::$app->user->identity->id;
                        $aditional->UB = Yii::$app->user->identity->id;
                        $aditional->DOC = date('Y-m-d');
                        if (!empty($aditional->label))
                                $aditional->save();
                }
        }

        /*
         * for updating additional data
         */

        public function AddAditionalUpdate($update) {
                $arr = [];
                $i = 0;
                foreach ($update as $key => $val) {
                        $arr[$key]['label'] = $val['label'][0];
                        $arr[$key]['value'] = $val['value'][0];
                        $arr[$key]['comment'] = $val['comment'][0];
                        $i++;
                }
                foreach ($arr as $key => $value) {
                        $aditional = PortCallDataAdditional::findOne($key);
                        $aditional->label = $value['label'];
                        $aditional->value = Yii::$app->ChangeDateFormate->SingleDateFormat($value['value']);
                        $aditional->comment = $value['comment'];
                        $aditional->save();
                }
        }

        /*
         * for delete portcall data additional data
         */

        public function AddAditionalDelete($param) {
                $vals = rtrim($param, ',');
                $vals = explode(',', $vals);
                foreach ($vals as $val) {
                        PortCallDataAdditional::findOne($val)->delete();
                }
        }

        /*
         * for saving portcall data draft and rob
         */

        public function SavePortcallDraftRob($model_rob, $model_draft) {
                Yii::$app->SetValues->Attributes($model_draft);
                Yii::$app->SetValues->Attributes($model_rob);
                Yii::$app->ChangeDateFormate->DateFormat($model_draft);
                $model_draft->save();
                $model_rob->save();
        }

        /*
         * for add stages
         */

        public function AddStages($model, $model_appointment) {
                if (!empty($model->eta)) {
                        $model_appointment->stage = 1;
                        $model_appointment->UB = Yii::$app->user->identity->id;
                        $model_appointment->save();
                }
                if (!empty($model->eosp)) {
                        if ($model_appointment->stage != 2 && $model_appointment->stage < 2) {
                                $model_appointment->stage = 2;
                                $model_appointment->UB = Yii::$app->user->identity->id;
                                $model_appointment->save();
                        }
                }
                if (!empty($model->all_fast)) {
                        if ($model_appointment->stage != 3 && $model_appointment->stage < 3) {
                                $model_appointment->stage = 3;
                                $model_appointment->UB = Yii::$app->user->identity->id;
                                $model_appointment->save();
                        }
                }
                if (!empty($model->cast_off)) {
                        if ($model_appointment->stage != 4 && $model_appointment->stage < 4) {
                                $model_appointment->stage = 4;
                                $model_appointment->UB = Yii::$app->user->identity->id;
                                $model_appointment->save();
                        }
                }
        }

        /*
         * Check all the port call data related tables are empty
         * if empty set values an save
         */

        public function Check($id, $model, $model_draft, $model_rob, $model_imigration) {
                if ($model != null && $model_draft != null && $model_rob != null && $model_imigration != null && $model_port_cargo_details != null) {
                        return true;
                } else {
                        if ($model == null) {
                                $model = new PortCallData();
                                $model->appointment_id = $id;
                                $model->save();
                        }
                        if ($model_draft == null) {
                                $model_draft = new PortCallDataDraft();
                                $model_draft->appointment_id = $id;
                                $model_draft->save();
                        }
                        if ($model_rob == null) {
                                $model_rob = new PortCallDataRob();
                                $model_rob->appointment_id = $id;
                                $model_rob->save();
                        }
                        if ($model_imigration == null) {
                                $model_imigration = new ImigrationClearance();
                                $model_imigration->appointment_id = $id;
                                $model_imigration->save();
                        }
                        return true;
                }
        }

        /**
         * Deletes an existing PortCallData model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return mixed
         */
        public function actionDelete($id) {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
        }

        /*
         * for complete portcall data and return to close estimate for further processig
         */

        public function actionPortcallComplete($id) {
                $appointment = Appointment::findOne($id);
                $ports = PortCallData::findAll(['appointment_id' => $id]);
                if (!empty($ports) && $appointment->stage == 4) {
                        $appointment->stage = 6;
                        $appointment->UB = Yii::$app->user->identity->id;
                        $appointment->save();
                        return $this->redirect(['/appointment/close-estimate/add', 'id' => $appointment->id]);
                } else {
                        Yii::$app->getSession()->setFlash('porterror', 'Portcall Data Not Completed..');
                        return $this->redirect(['update', 'id' => $id]);
                }
        }

        /**
         * Finds the PortCallData model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return PortCallData the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id) {
                if (($model = PortCallData::findOne($id)) !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }

        /*
         * for add port cargo details and port stoppages
         */

        public function actionPortBreak() {
                $id = $_POST['app_id'];
                $model_port_cargo_details = PortCargoDetails::findOne(['appointment_id' => $id]);
                if ($model_port_cargo_details == '') {
                        $model_port_cargo_details = new PortCargoDetails;
                } else {
                        $model_port_cargo_details = PortCargoDetails::findOne(['appointment_id' => $id]);
                }
                if ($model_port_cargo_details->load(Yii::$app->request->post())) {
                        $model_port_cargo_details = $this->saveportcargodetails($model_port_cargo_details, $id);
                }

                if (isset($_POST['create']) && $_POST['create'] != '') {
                        $this->PortStoppageCreate($_POST['create'], $id);
                }
                if (isset($_POST['updatee']) && $_POST['updatee'] != '') {
                        $this->PortStoppageUpdate($_POST['updatee']);
                }
                if (isset($_POST['delete_port_stoppages']) && $_POST['delete_port_stoppages'] != '') {
                        $this->PortStoppageDelete($_POST['delete_port_stoppages']);
                }
                return $this->redirect(['update', 'id' => $id]);
        }

        /*
         * for save port cargo details
         */

        public function SavePortCargoDetails($model_port_cargo_details, $id) {
                $data = PortCallData::findOne(['appointment_id' => $id]);
                $appointment = Appointment::findOne(['id' => $id]);
                Yii::$app->SetValues->Attributes($model_port_cargo_details);
                if (!empty($model_port_cargo_details->loaded_quantity)) {
                        $appointment->quantity = $model_port_cargo_details->loaded_quantity;
                        $appointment->save();
                }
                $model_port_cargo_details->appointment_id = $id;
                $model_port_cargo_details->port_call_id = $data->id;
                $model_port_cargo_details->save();
                return $model_port_cargo_details;
        }

        /*
         * for create port stoppages delay
         */

        public function PortStoppageCreate($create, $id) {
                $arr = [];
                $i = 0;
                foreach ($create['from'] as $val) {
                        $arr[$i]['from'] = $val;
                        $i++;
                }
                $i = 0;
                foreach ($create['too'] as $val) {
                        $arr[$i]['too'] = $val;
                        $i++;
                }
                $i = 0;
                foreach ($create['comment'] as $val) {
                        $arr[$i]['comment'] = $val;
                        $i++;
                }
                $this->SavePortStoppage($arr, $id);
        }

        /*
         * for save portstoppages delay
         */

        public function SavePortStoppage($arr, $id) {
                foreach ($arr as $val) {
                        $port_stoppages = new PortStoppages;
                        $port_stoppages->appointment_id = $id;
                        $port_stoppages->stoppage_from = Yii::$app->ChangeDateFormate->SingleDateFormat($val['from']);
                        $port_stoppages->stoppage_to = Yii::$app->ChangeDateFormate->SingleDateFormat($val['too']);
                        $port_stoppages->comment = $val['comment'];
                        $port_stoppages->status = 1;
                        $port_stoppages->CB = Yii::$app->user->identity->id;
                        $port_stoppages->UB = Yii::$app->user->identity->id;
                        $port_stoppages->DOC = date('Y-m-d');
                        if (!empty($port_stoppages->comment))
                                $port_stoppages->save();
                }
        }

        /*
         * for updating port stoppages delay
         */

        public function PortStoppageUpdate($update) {
                $arr = [];
                $i = 0;
                foreach ($update as $key => $val) {
                        $arr[$key]['from'] = $val['from'][0];
                        $arr[$key]['to'] = $val['to'][0];
                        $arr[$key]['comment'] = $val['comment'][0];
                        $i++;
                }
                foreach ($arr as $key => $value) {

                        $port_stoppages = PortStoppages::findOne($key);
                        $port_stoppages->stoppage_from = Yii::$app->ChangeDateFormate->SingleDateFormat($value['from']);
                        $port_stoppages->stoppage_to = Yii::$app->ChangeDateFormate->SingleDateFormat($value['to']);
                        $port_stoppages->comment = $value['comment'];
                        if ($port_stoppages->comment != '') {
                                $port_stoppages->save();
                        }
                }
        }

        /*
         * for delete port stoppages delay
         */

        public function PortStoppageDelete($param) {
                $vals = rtrim($param, ',');
                $vals = explode(',', $vals);
                foreach ($vals as $val) {
                        PortStoppages::findOne($val)->delete();
                }
        }

        /*
         * This functon called from 'report' view
         * for find and seperate the fields into date with time and without time
         */

        public function portcallReport($data, $label) {
                $arr = [];
                $check = ['id', 'appointment_id', 'additional_info', 'additional_info', 'comments', 'status', 'CB', 'UB', 'DOC', 'DOU', 'eta', 'ets', 'immigration_commenced', 'immigartion_completed', 'fasop', 'cleared_channel', 'eta_next_port', 'cosp', 'cast_off', 'lastline_away', 'pob_outbound'];
                $i = 0;
                $old = strtotime('1999-01-01 00:00:00');
                foreach ($data as $key => $value) {
                        if ($value != '' && $value != '0000-00-00 00:00:00' && strtotime($value) > $old) {
                                if (!in_array($key, $check)) {
                                        $mins = date('H:i:s', strtotime($value));
                                        if ($mins != '00:00:00') {
                                                $arr[$label]['mins'][$data->getAttributeLabel($key)] = $value;
                                        } else {
                                                $arr[$label]['no_mins'][$data->getAttributeLabel($key)] = $value;
                                        }
                                }
                        }
                }

                $port_additional = PortCallDataAdditional::findAll(['appointment_id' => $data->appointment_id]);
                foreach ($port_additional as $key => $value) {
                        if ($value->value != '' && $value->value != '0000-00-00 00:00:00' && strtotime($value->value) > $old) {
                                if (!in_array($value->label, $check)) {
                                        $mins = date('H:i:s', strtotime($value->value));
                                        if ($mins != '00:00:00') {
                                                $arr[$label]['mins'][$value->label] = $value->value;
                                        } else {
                                                $arr[$label]['no_mins'][$value->label] = $value->value;
                                        }
                                }
                        }
                }
                $ports_imigration = ImigrationClearance::findOne(['appointment_id' => $data->appointment_id]);
                foreach ($ports_imigration as $key => $value) {
                        if ($value != '' && $value != '0000-00-00 00:00:00' && strtotime($value) > $old) {
                                $check = ['id', 'appointment_id', 'status', 'CB', 'UB', 'DOC', 'DOU'];
                                if (!in_array($key, $check)) {
                                        $mins = date('H:i:s', strtotime($value));
                                        if ($mins != '00:00:00') {
                                                $arr[$label]['mins'][$ports_imigration->getAttributeLabel($key)] = $value;
                                        } else {
                                                $arr[$label]['no_mins'][$ports_imigration->getAttributeLabel($key)] = $value;
                                        }
                                }
                        }
                }
                return $arr;
        }

        /*
         * for upload files
         */

        public function actionUploads() {
                $model_upload = new UploadFile();
                if ($model_upload->load(Yii::$app->request->post())) {
                        $files = UploadedFile::getInstances($model_upload, 'filee');

                        if (Yii::$app->UploadFile->Upload($files, $model_upload)) {

                                return $this->redirect(Yii::$app->request->referrer);
                        }
                }
        }

        /*
         * for generate SOF Report
         */

        public function actionReports() {
                $id = $_POST['app_id'];
                $check = $_POST['check'];
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $ports_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $ports_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $ports_cargo = PortCargoDetails::findOne(['appointment_id' => $id]);
                $ports_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                $ports_imigration = ImigrationClearance::findOne(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);
                echo $content = $this->renderPartial('report', [
            'appointment' => $appointment,
            'ports' => $ports,
            'ports_draft' => $ports_draft,
            'ports_rob' => $ports_rob,
            'ports_cargo' => $ports_cargo,
            'ports_additional' => $ports_additional,
            'port_stoppages' => $port_stoppages,
            'ports_imigration' => $ports_imigration,
            'check' => $check,
                ]);
                exit;
        }

        /*
         * for generating Sailing Report
         */

        public function actionSailing($id) {
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $ports_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $ports_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $ports_cargo = PortCargoDetails::findOne(['appointment_id' => $id]);
                $ports_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                $ports_imigration = ImigrationClearance::findOne(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);
                echo $content = $this->renderPartial('sailing_report', [
            'appointment' => $appointment,
            'ports' => $ports,
            'ports_draft' => $ports_draft,
            'ports_rob' => $ports_rob,
            'ports_cargo' => $ports_cargo,
            'ports_additional' => $ports_additional,
            'port_stoppages' => $port_stoppages,
            'ports_imigration' => $ports_imigration,
                ]);
                exit;
        }

        /*
         * for generate Arrival Report
         */

        public function actionArrival($id) {
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $ports_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $ports_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $ports_cargo = PortCargoDetails::findOne(['appointment_id' => $id]);
                $ports_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                $port_imigration = ImigrationClearance::findAll(['appointment_id' => $id]);
                $appointment = Appointment::findOne($id);
                echo $content = $this->renderPartial('arrival_report', [
            'appointment' => $appointment,
            'ports' => $ports,
            'ports_draft' => $ports_draft,
            'ports_rob' => $ports_rob,
            'ports_cargo' => $ports_cargo,
            'ports_additional' => $ports_additional,
            'port_stoppages' => $port_stoppages,
            'port_imigration' => $port_imigration,
                ]);
                exit;
        }

        /*
         * for remove uploaded file path
         */

        public function actionRemove($path) {
                if (Yii::$app->session['post']['id'] == 1) {
                        unlink($path);
                }
                return $this->redirect(Yii::$app->request->referrer);
        }

}
