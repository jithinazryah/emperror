<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "actual_funding".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $service_id
 * @property double $fda_amount
 * @property double $actual_amount
 * @property double $amount_difference
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class ActualFunding extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'actual_funding';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['appointment_id', 'service_id', 'status', 'CB', 'UB', 'close_estimate_id'], 'integer'],
                        [['fda_amount', 'actual_amount', 'amount_difference'], 'number'],
                        [['CB', 'UB', 'DOC', 'unit_rate', 'unit'], 'required'],
                        [['DOC', 'DOU', 'supplier'], 'safe'],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'appointment_id' => 'Appointment ID',
                    'service_id' => 'Service ID',
                    'fda_amount' => 'Fda Amount',
                    'actual_amount' => 'Actual Amount',
                    'amount_difference' => 'Amount Difference',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

}
