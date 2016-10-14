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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ImigrationClearance;
use common\models\PortStoppages;

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
                if ($model_port_cargo_details == '')
                        $model_port_cargo_details = new PortCargoDetails;

                if (empty($model_appointment))
                        throw new \yii\web\HttpException(404, 'This Appointment could not be found.Eroor Code:1001');
                $model_add = new PortCallDataAdditional();

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
                } else if ($model_rob->load(Yii::$app->request->post()) && $model_draft->load(Yii::$app->request->post())) {
                        $this->saveportcalldraftrob($model_rob, $model_draft);
                }

                //$model_immigration = new ImigrationClearance();
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
                ]);
        }

        public function SavePortcallData($model) {
                Yii::$app->SetValues->Attributes($model);
                $this->dateformat($model, $_POST['PortCallData']);
                $model->save();
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
                                $aditional->value = $val['valuee'];
                                $aditional->comment = $val['comment'];
                                $aditional->status = 1;
                                $aditional->CB = Yii::$app->user->identity->id;
                                $aditional->UB = Yii::$app->user->identity->id;
                                $aditional->DOC = date('Y-m-d');
                                $aditional->value = $this->changeformat($aditional->value);
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
                                $aditional->value = $value['value'];
                                $aditional->comment = $value['comment'];
                                if ($aditional->value != '') {
                                        if (strpos($aditional->value, '-') == false) {
                                                $aditional->value = $this->changeformat($aditional->value);
                                        }
                                }

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
                if ($model_draft->validate() && $model_rob->validate()) {
                        $model_draft->intial_survey_commenced = $this->changeformat($model_draft->intial_survey_commenced);
                        $model_draft->finial_survey_commenced = $this->changeformat($model_draft->finial_survey_commenced);
                        $model_draft->intial_survey_completed = $this->changeformat($model_draft->intial_survey_completed);
                        $model_draft->finial_survey_completed = $this->changeformat($model_draft->finial_survey_completed);
                        $model_draft->save();
                        $model_rob->save();
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

        public function actionPortcallConmplete($id) {
                $appointment = Appointment::findOne($id);
//                $ports = PortCallData::findAll(['apponitment_id' => $id]);
//                if (!empty($ports)) {
//                        $appointment->stage = 2;
//                        $appointment->sub_stages = 2;
//                        $appointment->save();
//                        return $this->redirect(['/appointment/close-estimate/add', 'id' => $appointment->id]);
//                } else {
//                        
//                }
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

        public function DateFormat($model, $data) {
                if ($model != null && $data != '') {
                        $a = ['additional_info', 'comments', 'status'];
                        foreach ($data as $key => $dta) {
                                if (!in_array($key, $a)) {
                                        if (strlen($dta) < 15 && $dta != NULL)
                                                $model->$key = $this->ChangeFormat($dta);
                                }
                        }
                }
                return $model;
        }

        public function ChangeFormat($data) {
                $day = substr($data, 0, 2);
                $month = substr($data, 2, 2);
                $year = substr($data, 4, 4);
                $hour = substr($data, 9, 2);
                $min = substr($data, 11, 2);

//        echo $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min.':00 </br>';
//        echo '2016-08-17 00:00:00';
//        exit;
                return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':00';
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
                        foreach ($_POST['create']['label'] as $val) {
                                $arr[$i]['label'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create']['valuee'] as $val) {
                                $arr[$i]['valuee'] = $val;
                                $i++;
                        }
                        foreach ($arr as $val) {
                                $port_break = new PortBreakTimings;
                                $port_break->appointment_id = $id;
                                $port_break->label = $val['label'];
                                $port_break->value = $val['valuee'];
                                $port_break->status = 1;
                                $port_break->CB = Yii::$app->user->identity->id;
                                $port_break->UB = Yii::$app->user->identity->id;
                                $port_break->DOC = date('Y-m-d');
                                $port_break->value = $this->changeformat($port_break->value);
                                if (!empty($port_break->label))
                                        $port_break->save();
                        }
                }
                
                if (isset($_POST['updatee']) && $_POST['updatee'] != '') {
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['updatee'] as $key => $val) {
                                $arr[$key]['label'] = $val['label'][0];
                                $arr[$key]['value'] = $val['value'][0];
                                $i++;
                        }
                        foreach ($arr as $key => $value) {

                                $port_break = PortBreakTimings::findOne($key);
                                $port_break->label = $value['label'];
                                $port_break->value = $value['value'];
                                if ($port_break->value != '') {
                                        if (strpos($port_break->value, '-') == false) {
                                                $port_break->value = $this->changeformat($port_break->value);
                                        }
                                }
                                $port_break->save();
                        }
                }
                if (isset($_POST['delete_port_break']) && $_POST['delete_port_break'] != '') {
                        $vals = rtrim($_POST['delete_port_break'], ',');
                        $vals = explode(',', $vals);
                        foreach ($vals as $val) {
                                PortBreakTimings::findOne($val)->delete();
                        }
                }
               
                if (isset($_POST['create1']) && $_POST['create1'] != '') {
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['create1']['stoppage_from'] as $val) {
                                $arr[$i]['from'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create1']['stoppage_to'] as $val) {
                                $arr[$i]['to'] = $val;
                                $i++;
                        }
                        $i=0;
                        foreach ($_POST['create1']['comment'] as $val) {
                                $arr[$i]['comment'] = $val;
                                $i++;
                        }
                        
                        foreach ($arr as $val) {
                                $port_stoppages = new PortStoppages;
                                $port_stoppages->appointment_id = $id;
                                $port_stoppages->stoppage_from = $val['from'];
                                $port_stoppages->stoppage_to = $val['to'];
                                $port_stoppages->comment = $val['comment'];
                                $port_stoppages->status = 1;
                                $port_stoppages->CB = Yii::$app->user->identity->id;
                                $port_stoppages->UB = Yii::$app->user->identity->id;
                                $port_stoppages->DOC = date('Y-m-d');
                                $port_stoppages->stoppage_from = $this->changeformat($port_stoppages->stoppage_from);
                                $port_stoppages->stoppage_to = $this->changeformat($port_stoppages->stoppage_to);
                                if (!empty($port_stoppages->comment))
                                        $port_stoppages->save();
                        }
                }
                if (isset($_POST['updatee1']) && $_POST['updatee1'] != '') {
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['updatee'] as $key => $val) {
                                $arr[$key]['from'] = $val['stoppage_from'][0];
                                $arr[$key]['to'] = $val['stoppage_to'][0];
                                $arr[$key]['comment'] = $val['comment'][0];
                                $i++;
                        }
                        
                        foreach ($arr as $key => $value) {

                                $port_stoppages = PortStoppages::findOne($key);
                                $port_stoppages->stoppage_from = $value['from'];
                                $port_stoppages->stoppage_to = $value['to'];
                                $port_stoppages->comment = $value['comment'];
                                if ($port_stoppages->comment != '') {
                                        if (strpos($port_stoppages->stoppage_from, '-') == false) {
                                                $port_stoppages->stoppage_from = $this->changeformat($port_stoppages->stoppage_from);
                                        }
                                        if (strpos($port_stoppages->stoppage_to, '-') == false) {
                                                $port_stoppages->stoppage_to = $this->changeformat($port_stoppages->stoppage_to);
                                        }
                                }
                                $port_stoppages->save();
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
                Yii::$app->SetValues->Attributes($model_port_cargo_details);
                $model_port_cargo_details->appointment_id = $id;
                $model_port_cargo_details->port_call_id = $data->id;
                $model_port_cargo_details->save();
                return $model_port_cargo_details;
        }
        
        public function actionPortStoppages() {
                $id = $_POST['app_id'];
                if (isset($_POST['create1']) && $_POST['create1'] != '') {
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['create1']['stoppage_from'] as $val) {
                                $arr[$i]['from'] = $val;
                                $i++;
                        }
                        $i = 0;
                        foreach ($_POST['create1']['stoppage_to'] as $val) {
                                $arr[$i]['to'] = $val;
                                $i++;
                        }
                        $i=0;
                        foreach ($_POST['create1']['comment'] as $val) {
                                $arr[$i]['comment'] = $val;
                                $i++;
                        }
                        
                        foreach ($arr as $val) {
                                $port_stoppages = new PortStoppages;
                                $port_stoppages->appointment_id = $id;
                                $port_stoppages->stoppage_from = $val['from'];
                                $port_stoppages->stoppage_to = $val['to'];
                                $port_stoppages->comment = $val['comment'];
                                $port_stoppages->status = 1;
                                $port_stoppages->CB = Yii::$app->user->identity->id;
                                $port_stoppages->UB = Yii::$app->user->identity->id;
                                $port_stoppages->DOC = date('Y-m-d');
                                $port_stoppages->stoppage_from = $this->changeformat($port_stoppages->stoppage_from);
                                $port_stoppages->stoppage_to = $this->changeformat($port_stoppages->stoppage_to);
                                if (!empty($port_stoppages->comment))
                                        $port_stoppages->save();
                        }
                }
                if (isset($_POST['updatee1']) && $_POST['updatee1'] != '') {
                        $arr = [];
                        $i = 0;
                        foreach ($_POST['updatee'] as $key => $val) {
                                $arr[$key]['from'] = $val['stoppage_from'][0];
                                $arr[$key]['to'] = $val['stoppage_to'][0];
                                $arr[$key]['comment'] = $val['comment'][0];
                                $i++;
                        }
                        
                        foreach ($arr as $key => $value) {

                                $port_stoppages = PortStoppages::findOne($key);
                                $port_stoppages->stoppage_from = $value['from'];
                                $port_stoppages->stoppage_to = $value['to'];
                                $port_stoppages->comment = $value['comment'];
                                if ($port_stoppages->comment != '') {
                                        if (strpos($port_stoppages->stoppage_from, '-') == false) {
                                                $port_stoppages->stoppage_from = $this->changeformat($port_stoppages->stoppage_from);
                                        }
                                        if (strpos($port_stoppages->stoppage_to, '-') == false) {
                                                $port_stoppages->stoppage_to = $this->changeformat($port_stoppages->stoppage_to);
                                        }
                                }
                                $port_stoppages->save();
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

        public function portcallReport($data, $label) {
                $arr = [];
                $check = ['id', 'appointment_id', 'additional_info', 'additional_info', 'comments', 'status', 'CB', 'UB', 'DOC', 'DOU', 'eta', 'ets', 'immigration_commenced', 'immigartion_completed','fasop','cleared_channel','eta_next_port'];
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
                return $arr;
        }

        public function actionReports($id) {
                $ports = PortCallData::findOne(['appointment_id' => $id]);
                $ports_draft = PortCallDataDraft::findOne(['appointment_id' => $id]);
                $ports_rob = PortCallDataRob::findOne(['appointment_id' => $id]);
                $ports_cargo = PortCargoDetails::findOne(['appointment_id' => $id]);
                $ports_additional = PortCallDataAdditional::findAll(['appointment_id' => $id]);
                $port_stoppages = PortStoppages::findAll(['appointment_id' => $id]);
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

}
