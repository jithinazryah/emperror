<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appointment".
 *
 * @property integer $id
 * @property integer $vessel_type
 * @property integer $vessel
 * @property integer $port_of_call
 * @property integer $terminal
 * @property string $birth_no
 * @property string $appointment_no
 * @property string $no_of_principal
 * @property integer $principal
 * @property integer $nominator
 * @property integer $charterer
 * @property integer $shipper
 * @property string $purpose
 * @property string $cargo
 * @property string $quantity
 * @property string $last_port
 * @property string $next_port
 * @property string $eta
 * @property integer $stage
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Appointment extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'appointment';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                    [['vessel_type', 'port_of_call'], 'required'],
                    [['vessel_type', 'vessel', 'port_of_call', 'tug', 'barge', 'nominator', 'terminal', 'charterer', 'shipper', 'stage', 'sub_stages', 'status', 'CB', 'UB'], 'integer'],
                    [['eta', 'DOC', 'DOU','cargo_details'], 'safe'],
                    [['birth_no', 'appointment_no'], 'string', 'max' => 50],
                    [['no_of_principal', 'quantity'], 'string', 'max' => 15],
                    [['purpose', 'last_port', 'next_port'], 'string', 'max' => 200],
                    [['cargo'], 'string', 'max' => 100],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'vessel_type' => 'Vessel Type',
                    'vessel' => 'Vessel',
                    'tug' => 'Tug',
                    'barge' => 'Barge',
                    'port_of_call' => 'Port Of Call',
                    'terminal' => 'Terminal',
                    'birth_no' => 'Berth No',
                    'appointment_no' => 'Appointment No',
                    'no_of_principal' => 'No Of Principal',
                    'principal' => 'Principal',
                    'nominator' => 'Nominator',
                    'charterer' => 'Charterer',
                    'shipper' => 'Shipper',
                    'purpose' => 'Purpose',
                    'cargo' => 'Cargo',
                    'cargo_details' => 'Cargo Details',
                    'quantity' => 'Quantity',
                    'last_port' => 'Last Port',
                    'next_port' => 'Next Port',
                    'eta' => 'ETA',
                    'stage' => 'Stage',
                    'sub_stages' => 'Sub Stages',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

        public function getVessel0() {
                return $this->hasOne(Vessel::className(), ['id' => 'vessel_type']);
        }

        /**
         * @return \yii\db\ActiveQuery 
         */
        public function getVesselType() {
                return $this->hasOne(VesselType::className(), ['id' => 'vessel_type']);
        }
        /**
         * @return \yii\db\ActiveQuery 
         */
        public function getPortOfCall() {
                return $this->hasOne(Ports::className(), ['id' => 'port_of_call']);
        }
        /**
         * @return \yii\db\ActiveQuery 
         */
        public function getPurpose0() {
                return $this->hasOne(Purpose::className(), ['id' => 'purpose']);
        }
        /**
         * @return \yii\db\ActiveQuery 
         */
        public function getTerminal0() {
                return $this->hasOne(Terminal::className(), ['id' => 'terminal']);
        }
        
         public function getDebtorName($debtor_id) {
                $data = Debtor::findOne(['id' => $debtor_id]);
                return $data->principal_name;
        }
         public function getInvoiceAddress($principal) {
                $data = Debtor::findOne(['id' => $principal]);
                return $data->invoicing_address;
        }
        public function getClintCode($principal) {
                $data = Debtor::findOne(['id' => $principal]);
                return $data->principal_id;
        }

}
