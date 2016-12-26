<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fda_report".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $principal_id
 * @property string $invoice_number
 * @property string $sub_invoice
 * @property string $report
 */
class FdaReport extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'fda_report';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['appointment_id', 'principal_id'], 'integer'],
                        [['report'], 'string'],
                        [['invoice_number', 'sub_invoice'], 'string', 'max' => 50],
                        [['status', 'CB', 'UB'], 'integer'],
                        [['DOC', 'DOU'], 'safe'],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'appointment_id' => 'Appointment ID',
                    'principal_id' => 'Principal ID',
                    'invoice_number' => 'Invoice Number',
                    'sub_invoice' => 'Sub Invoice',
                    'report' => 'Report',
                ];
        }

}
