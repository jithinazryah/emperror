<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_call_data_rob".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $fo_arrival_unit
 * @property integer $fo_arrival_quantity
 * @property integer $do_arrival_unit
 * @property integer $do_arrival_quantity
 * @property integer $go_arrival_unit
 * @property integer $go_arrival_quantity
 * @property integer $lo_arrival_unit
 * @property integer $lo_arrival_quantity
 * @property integer $fresh_water_arrival_unit
 * @property integer $fresh_water_arrival_quantity
 * @property integer $fo_sailing_unit
 * @property integer $fo_sailing_quantity
 * @property integer $do_sailing_unit
 * @property integer $do_sailing_quantity
 * @property integer $go_sailing_unit
 * @property integer $go_sailing_quantity
 * @property integer $lo_sailing_unit
 * @property integer $lo_sailing_quantity
 * @property integer $fresh_water_sailing_unit
 * @property integer $fresh_water_sailing_quantity
 * @property integer $additional_info
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortCallDataRob extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'port_call_data_rob';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                    [['appointment_id'], 'required'],
                    [['appointment_id', 'fo_arrival_unit', 'do_arrival_unit', 'go_arrival_unit', 'lo_arrival_unit', 'fresh_water_arrival_unit', 'fo_sailing_unit', 'do_sailing_unit', 'go_sailing_unit', 'lo_sailing_unit', 'fresh_water_sailing_unit', 'additional_info', 'status', 'CB', 'UB'], 'integer'],
                    [['fo_arrival_quantity', 'do_arrival_quantity', 'go_arrival_quantity', 'lo_arrival_quantity', 'fresh_water_arrival_quantity','fo_sailing_quantity','do_sailing_quantity','go_sailing_quantity','lo_sailing_quantity','lo_sailing_quantity', 'fresh_water_sailing_quantity','rob_received'], 'safe'],
                    [['comments'], 'string'],
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
                    'fo_arrival_unit' => 'FO Arrival Unit',
                    'fo_arrival_quantity' => 'FO Arrival Quantity',
                    'do_arrival_unit' => 'DO Arrival Unit',
                    'do_arrival_quantity' => 'DO Arrival Quantity',
                    'go_arrival_unit' => 'GO Arrival Unit',
                    'go_arrival_quantity' => 'GO Arrival Quantity',
                    'lo_arrival_unit' => 'LO Arrival Unit',
                    'lo_arrival_quantity' => 'LO Arrival Quantity',
                    'fresh_water_arrival_unit' => 'Fresh Water Arrival Unit',
                    'fresh_water_arrival_quantity' => 'Fresh Water Arrival Quantity',
                    'fo_sailing_unit' => 'FO Sailing Unit',
                    'fo_sailing_quantity' => 'FO Sailing Quantity',
                    'do_sailing_unit' => 'DO Sailing Unit',
                    'do_sailing_quantity' => 'DO Sailing Quantity',
                    'go_sailing_unit' => 'GO Sailing Unit',
                    'go_sailing_quantity' => 'GO Sailing Quantity',
                    'lo_sailing_unit' => 'LO Sailing Unit',
                    'lo_sailing_quantity' => 'LO Sailing Quantity',
                    'fresh_water_sailing_unit' => 'Fresh Water Sailing Unit',
                    'fresh_water_sailing_quantity' => 'Fresh Water Sailing Quantity',
                    'additional_info' => 'Additional Info',
                    'rob_received' => 'ROB Received',
                    'comments' => 'Comments',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

        public function getAppointment() {
                return $this->hasOne(Appointment::className(), ['id' => 'appointment_id']);
        }

}
