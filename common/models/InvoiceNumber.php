<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_number".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $invoice_type
 * @property integer $invoice_number
 * @property string $date_time
 */
class InvoiceNumber extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'invoice_number';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['appointment_id', 'invoice_type', 'invoice_number'], 'integer'],
                        [['date_time', 'estimate_id'], 'safe'],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'appointment_id' => 'Appointment ID',
                    'estimate_id' => 'Estimate ID',
                    'invoice_type' => 'Invoice Type',
                    'invoice_number' => 'Invoice Number',
                    'date_time' => 'Date Time',
                ];
        }

}
