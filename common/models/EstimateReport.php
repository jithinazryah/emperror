<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "estimate_report".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property string $date_time
 * @property string $report
 * @property integer $status
 */
class EstimateReport extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'estimate_report';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['appointment_id', 'status'], 'integer'],
                        [['date_time', 'DOC', 'DOU', 'CB', 'UB'], 'safe'],
                        [['report'], 'string'],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'appointment_id' => 'Appointment ID',
                    'date_time' => 'Date Time',
                    'report' => 'Report',
                    'status' => 'Status',
                ];
        }

}
