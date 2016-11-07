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
                $model = $this->dateformat($model, $model->attributes);
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
                $model = $this->dateformat($model);
                $model_imigration = $this->dateformat($model_imigration);
                $model_draft = $this->dateformat($model_draft);
                foreach ($model_additional as $additional) {
                        $additional->value = $this->SingleDateFormat($additional->value);
                }
                foreach ($model_port_stoppages as $port_stoppages) {
                        $port_stoppages->stoppage_from = $this->SingleDateFormat($port_stoppages->stoppage_from);
                        $port_stoppages->stoppage_to = $this->SingleDateFormat($port_stoppages->stoppage_to);
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

        public function SavePortcallData($model, $model_imigration) {
                Yii::$app->SetValues->Attributes($model);
                Yii::$app->SetValues->Attributes($model_imigration);
                $this->dateformat($model);
                $this->dateformat($model_imigration);
                $model->save();
                $model_imigration->save();
                if (isset($_POST['create']) && $_POST['create'] != '') {
                        //echo 'create';exit;
                        $arr = [];
                        $i = 0;

                        foreach ($_POST['create']['label'] as $val) {
                                $arr[$i]['label'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create']['valuee'] as $val) {
                                $arr[$i]['valuee'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create']['comment'] as $val) {
                                $arr[$i]['comment'] = $val;
                                $i++;
                        }
                        foreach ($arr as $val) {
                                $aditional = new PortCallDataAdditional;
                                $aditional->appointment_id = $model->appointment_id;
                                $aditional->label = $val['label'];
                                $aditional->value = $this->SingleDateFormat($val['valuee']);
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
                if (isset($_POST['updatee']) && $_POST['updatee'] != '') {
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['updatee'] as $key => $val) {
                                $arr[$key]['label'] = $val['label'][0];
                                $arr[$key]['value'] = $val['value'][0];
                                $arr[$key]['comment'] = $val['comment'][0];
                                $i++;
                        }
                        foreach ($arr as $key => $value) {
                                $aditional = PortCallDataAdditional::findOne($key);
                                $aditional->label = $value['label'];
                                $aditional->value = $this->SingleDateFormat($value['value']);
                                $aditional->comment = $value['comment'];
                                $aditional->save();
                        }
                }
                if (isset($_POST['delete_port_vals']) && $_POST['delete_port_vals'] != '') {
                        //echo 'delete';exit;
                        $vals = rtrim($_POST['delete_port_vals'], ',');
                        $vals = explode(',', $vals);
                        foreach ($vals as $val) {
                                PortCallDataAdditional::findOne($val)->delete();
                        }
                }
                return true;
        }

        public function SavePortcallDraftRob($model_rob, $model_draft) {
                Yii::$app->SetValues->Attributes($model_draft);
                Yii::$app->SetValues->Attributes($model_rob);
                $this->dateformat($model_draft);
                $model_draft->save();
                $model_rob->save();
        }

        public function AddStages($model, $model_appointment) {
                if (!empty($model->eta)) {
                        $model_appointment->stage = 1;
                        $model_appointment->save();
                }
                if (!empty($model->eosp)) {
                        if ($model_appointment->stage != 2 && $model_appointment->stage < 2) {
                                $model_appointment->stage = 2;
                                $model_appointment->save();
                        }
                }
                if (!empty($model->all_fast)) {
                        if ($model_appointment->stage != 3 && $model_appointment->stage < 3) {
                                $model_appointment->stage = 3;
                                $model_appointment->save();
                        }
                }
                if (!empty($model->cast_off)) {
                        if ($model_appointment->stage != 4 && $model_appointment->stage < 4) {
                                $model_appointment->stage = 4;
                                $model_appointment->save();
                        }
                }
        }

        public function Check($id, $model, $model_draft, $model_rob, $model_imigration) {
                //echo 'hai';exit;
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

        public function actionPortcallComplete($id) {
                $appointment = Appointment::findOne($id);
                $ports = PortCallData::findAll(['appointment_id' => $id]);
                if (!empty($ports) && $appointment->stage == 4) {
                        $appointment->stage = 5;
//                        $appointment->sub_stages = 2;
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

        public function DateFormat($model) {
                if (!empty($model)) {
                        $a = ['id', 'appointment_id', 'additional_info', 'comments', 'status', 'type', 'data_id', 'label', 'CB', 'UB', 'DOC', 'fwd_arrival_unit', 'fwd_arrival_quantity', 'aft_arrival_unit',
                            'aft_arrival_quantity', 'mean_arrival_unit', 'mean_arrival_quantity', 'fwd_sailing_unit', 'fwd_sailing_quantity', 'aft_sailing_unit', 'aft_sailing_quantity',
                            'mean_sailing_unit', 'mean_sailing_quantity',];
                        foreach ($model->attributes as $key => $dta) {
                                if (!in_array($key, $a)) {
                                        $model->$key = $this->SingleDateFormat($dta);
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
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['create']['from'] as $val) {
                                $arr[$i]['from'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create']['too'] as $val) {
                                $arr[$i]['too'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create']['comment'] as $val) {
                                $arr[$i]['comment'] = $val;
                                $i++;
                        }

                        foreach ($arr as $val) {
                                $port_stoppages = new PortStoppages;
                                $port_stoppages->appointment_id = $id;
                                $port_stoppages->stoppage_from = $this->SingleDateFormat($val['from']);
                                $port_stoppages->stoppage_to = $this->SingleDateFormat($val['too']);
                                $port_stoppages->comment = $val['comment'];
                                $port_stoppages->status = 1;
                                $port_stoppages->CB = Yii::$app->user->identity->id;
                                $port_stoppages->UB = Yii::$app->user->identity->id;
                                $port_stoppages->DOC = date('Y-m-d');
                                if (!empty($port_stoppages->comment))
                                        $port_stoppages->save();
                        }
                }
                if (isset($_POST['updatee']) && $_POST['updatee'] != '') {
                        $arr = [];
                        $i = 0;

                        foreach ($_POST['updatee'] as $key => $val) {
                                $arr[$key]['from'] = $val['from'][0];
                                $arr[$key]['to'] = $val['to'][0];
                                $arr[$key]['comment'] = $val['comment'][0];
                                $i++;
                        }
                        foreach ($arr as $key => $value) {

                                $port_stoppages = PortStoppages::findOne($key);
                                $port_stoppages->stoppage_from = $this->SingleDateFormat($value['from']);
                                $port_stoppages->stoppage_to = $this->SingleDateFormat($value['to']);
                                $port_stoppages->comment = $value['comment'];
                                if ($port_stoppages->comment != '') {
                                        $port_stoppages->save();
                                }
                        }
                }
                if (isset($_POST['delete_port_stoppages']) && $_POST['delete_port_stoppages'] != '') {
                        $vals = rtrim($_POST['delete_port_stoppages'], ',');
                        $vals = explode(',', $vals);
                        foreach ($vals as $val) {
                                PortStoppages::findOne($val)->delete();
                        }
                }
                return $this->redirect(['update', 'id' => $id]);
        }

        public function SavePortCargoDetails($model_port_cargo_details, $id) {
                $data = PortCallData::findOne(['appointment_id' => $id]);
                $appointment = Appointment::findOne(['id' => $id]);
                Yii::$app->SetValues->Attributes($model_port_cargo_details);
                if(!empty($model_port_cargo_details->loaded_quantity)){
                       $appointment->quantity = $model_port_cargo_details->loaded_quantity;
                       $appointment->save();
                }
                $model_port_cargo_details->appointment_id = $id;
                $model_port_cargo_details->port_call_id = $data->id;
                $model_port_cargo_details->save();
                return $model_port_cargo_details;
        }

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

        public function actionUploads() {
                $model_upload = new UploadFile();
                if ($model_upload->load(Yii::$app->request->post())) {
                        $files = UploadedFile::getInstances($model_upload, 'filee');

                        if (Yii::$app->UploadFile->Upload($files, $model_upload)) {

                                return $this->redirect(Yii::$app->request->referrer);
                        }
                }
        }

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
                // get your HTML raw content without any layouts or scripts
                $appointment = Appointment::findOne($id);
                //var_dump($appointment);exit;
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

                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    //'mode' => Pdf::MODE_CORE,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
//                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
//                    'destination' => Pdf::DEST_BROWSER,
                    // your html content input
                    'content' => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting 
                    'cssFile' => '@backend/web/css/pdf.css',
                        // any css to be embedded if required
                        //'cssInline' => '.kv-heading-1{font-size:18px}',
                        // set mPDF properties on the fly
                        //'options' => ['title' => 'Krajee Report Title'],
                        // call mPDF methods on the fly
                        /*                    'methods' => [
                          'SetHeader' => ['Estimated proforma generated on ' . date("d/m/Y h:m:s")],
                          'SetFooter' => ['|page {PAGENO}'],
                          ] */
                ]);

                // return the pdf output as per the destination setting
                return $pdf->render();
        }

        public function actionSailing($id) {
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $ports_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $ports_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $ports_cargo = PortCargoDetails::findOne(['appointment_id' => $id]);
                $ports_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                $ports_imigration = ImigrationClearance::findOne(['appointment_id' => $id]);
                // get your HTML raw content without any layouts or scripts
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
                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    //'mode' => Pdf::MODE_CORE,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
//                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
//                    'destination' => Pdf::DEST_BROWSER,
                    // your html content input
                    'content' => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting 
                    'cssFile' => '@backend/web/css/pdf.css',
                        // any css to be embedded if required
                        //'cssInline' => '.kv-heading-1{font-size:18px}',
                        // set mPDF properties on the fly
                        //'options' => ['title' => 'Krajee Report Title'],
                        // call mPDF methods on the fly
                        /*                    'methods' => [
                          'SetHeader' => ['Estimated proforma generated on ' . date("d/m/Y h:m:s")],
                          'SetFooter' => ['|page {PAGENO}'],
                          ] */
                ]);

                // return the pdf output as per the destination setting
                return $pdf->render();
        }

        public function actionArrival($id) {
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $ports_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $ports_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $ports_cargo = PortCargoDetails::findOne(['appointment_id' => $id]);
                $ports_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
                $port_imigration = ImigrationClearance::findAll(['appointment_id' => $id]);
                // get your HTML raw content without any layouts or scripts
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
                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    //'mode' => Pdf::MODE_CORE,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
//                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
//                    'destination' => Pdf::DEST_BROWSER,
                    // your html content input
                    'content' => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting 
                    'cssFile' => '@backend/web/css/pdf.css',
                        // any css to be embedded if required
                        //'cssInline' => '.kv-heading-1{font-size:18px}',
                        // set mPDF properties on the fly
                        //'options' => ['title' => 'Krajee Report Title'],
                        // call mPDF methods on the fly
                        /*                    'methods' => [
                          'SetHeader' => ['Estimated proforma generated on ' . date("d/m/Y h:m:s")],
                          'SetFooter' => ['|page {PAGENO}'],
                          ] */
                ]);

                // return the pdf output as per the destination setting
                return $pdf->render();
        }

        public function actionRemove($path) {
                unlink($path);
                return $this->redirect(Yii::$app->request->referrer);
        }

}
