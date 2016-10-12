<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_call_data_draft".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $data_id
 * @property string $intial_survey_commenced
 * @property string $intial_survey_completed
 * @property string $finial_survey_commenced
 * @property string $finial_survey_completed
 * @property integer $fwd_arrival_unit
 * @property integer $fwd_arrival_quantity
 * @property integer $aft_arrival_unit
 * @property integer $aft_arrival_quantity
 * @property integer $mean_arrival_unit
 * @property integer $mean_arrival_quantity
 * @property integer $fwd_sailing_unit
 * @property integer $fwd_sailing_quantity
 * @property integer $aft_sailing_unit
 * @property integer $aft_sailing_quantity
 * @property integer $mean_sailing_unit
 * @property integer $mean_sailing_quantity
 * @property integer $additional_info
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortCallDataDraft extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'port_call_data_draft';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['appointment_id'], 'required'],
            [['appointment_id', 'data_id', 'fwd_arrival_unit', 'aft_arrival_unit', 'mean_arrival_unit', 'fwd_sailing_unit', 'aft_sailing_unit', 'mean_sailing_unit', 'additional_info', 'status', 'CB', 'UB'], 'integer'],
            [['intial_survey_commenced', 'intial_survey_completed', 'finial_survey_commenced', 'finial_survey_completed', 'DOC', 'DOU', 'fwd_arrival_quantity', 'aft_arrival_quantity', 'mean_arrival_quantity', 'fwd_sailing_quantity', 'aft_sailing_quantity', 'mean_sailing_quantity'], 'safe'],
            [['comments'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'appointment_id' => 'Appointment ID',
            'data_id' => 'Data ID',
            'intial_survey_commenced' => 'Ullaging/ Sampling Commenced',
            'intial_survey_completed' => 'Ullaging/ Sampling Completed',
            'finial_survey_commenced' => 'Tank Inspection Commenced',
            'finial_survey_completed' => 'Tank Inspection Completed',
            'fwd_arrival_unit' => 'FWD',
            'fwd_arrival_quantity' => 'FWD',
            'aft_arrival_unit' => 'AFT',
            'aft_arrival_quantity' => 'AFT',
            'mean_arrival_unit' => 'MEAN',
            'mean_arrival_quantity' => 'MEAN',
            'fwd_sailing_unit' => 'FWD',
            'fwd_sailing_quantity' => 'FWD',
            'aft_sailing_unit' => 'AFT',
            'aft_sailing_quantity' => 'AFT',
            'mean_sailing_unit' => 'MEAN',
            'mean_sailing_quantity' => 'MEAN',
            'additional_info' => 'Additional Info',
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
