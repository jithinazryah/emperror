<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\modules\appointment\controllers;

use Yii;
use common\models\Appointment;
use common\models\Ports;
use common\models\PortCallData;
use common\models\PortCallDataDraft;
use common\models\PortCallDataRob;
use common\models\EstimatedProforma;
use common\models\CloseEstimate;
use common\models\AppointmentSearch;
use common\models\PortBreakTimings;
use common\models\Currency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ImigrationClearance;
use common\models\Report;
use common\models\ReportSearch;

class ReportController extends Controller {

        //put your code here
        public function init() {
                if (Yii::$app->user->isGuest)
                        $this->redirect(['/site/index']);

                if (Yii::$app->session['post']['appointments'] != 1)
                        $this->redirect(['/site/home']);
        }

        /**
         * @inheritdoc
         */
        public function behaviors() {
                return [
                    'access' => [
                        'class' => AccessControl::className(),
                        'only' => ['addBasic'],
                        'rules' => [
                                [
                                'actions' => ['appointmentNo'],
                                'allow' => true,
                                'roles' => ['?'],
                            ],
                        ],
                    ],
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ];
        }

        public function actionIndex() {
                $searchModel = new ReportSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                ]);
        }

        public function actionVesselType() {
                if (Yii::$app->request->isAjax) {
                        $vessel_type = $_POST['vessel_type'];
                        $vessel_datas = \common\models\Vessel::findAll(['vessel_type' => $vessel_type, 'status' => 1]);
                        $options = '<option value="">-Choose a Vessel-</option>';
                        foreach ($vessel_datas as $vessel_data) {
                                $options .= "<option value='" . $vessel_data->id . "'>" . $vessel_data->vessel_name . "</option>";
                        }

                        echo $options;
                }
        }

}
