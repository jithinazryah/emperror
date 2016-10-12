<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_call_data".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property string $eta
 * @property string $ets
 * @property string $eosp
 * @property string $arrived_anchorage
 * @property string $nor_tendered
 * @property string $dropped_anchor
 * @property string $anchor_aweigh
 * @property string $arrived_pilot_station
 * @property string $pob_inbound
 * @property string $first_line_ashore
 * @property string $all_fast
 * @property string $gangway_down
 * @property string $agent_on_board
 * @property string $immigration_commenced
 * @property string $immigartion_completed
 * @property string $cargo_commenced
 * @property string $cargo_completed
 * @property string $pob_outbound
 * @property string $lastline_away
 * @property string $cleared_channel
 * @property string $cosp
 * @property string $fasop
 * @property string $eta_next_port
 * @property integer $additional_info
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortCallData extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'port_call_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['appointment_id'], 'required'],
            [['appointment_id', 'additional_info', 'status', 'CB', 'UB'], 'integer'],
            [['eta', 'ets', 'eosp', 'arrived_anchorage', 'nor_tendered', 'dropped_anchor', 'anchor_aweigh', 'arrived_pilot_station', 'pob_inbound', 'first_line_ashore', 'all_fast', 'gangway_down', 'agent_on_board', 'immigration_commenced', 'immigartion_completed', 'cargo_commenced', 'cargo_completed', 'pob_outbound', 'lastline_away', 'cleared_channel', 'cosp', 'fasop', 'eta_next_port', 'DOC', 'DOU', 'cast_off', 'pilot_away', 'customs_clearance_onarrival','customs_clearance_ondeparture', 'hoses_connected', 'pre_discharge_safety', 'surveyor_on_board', 'sampling', 'tank_inspection_completed', 'hoses_disconnected', 'sbe', 'documentation_completed'], 'safe'],
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
            'eta' => 'ETA',
            'ets' => 'ETS',
            'eosp' => 'EOSP',
            'arrived_anchorage' => 'Arrived Anchorage',
            'nor_tendered' => 'NOR Tendered',
            'dropped_anchor' => 'Dropped Anchor',
            'anchor_aweigh' => 'Anchor Aweigh',
            'arrived_pilot_station' => 'Arrived Pilot Station',
            'pob_inbound' => 'POB(inbound)',
            'first_line_ashore' => 'First Line Ashore',
            'all_fast' => 'All Fast',
            'gangway_down' => 'Gangway Down',
            'agent_on_board' => 'Agent On Board',
            'immigration_commenced' => 'IMG Commenced',
            'immigartion_completed' => 'IMG Completed',
            'cargo_commenced' => 'Cargo Commenced',
            'cargo_completed' => 'Cargo Completed',
            'pob_outbound' => 'POB(outbound)',
            'lastline_away' => 'Lastline Away',
            'cleared_channel' => 'Cleared Channel',
            'cosp' => 'COSP',
            'fasop' => 'FASOP',
            'eta_next_port' => 'ETA Next Port',
            'pilot_away' => 'Pilot Away',
            'customs_clearance_onarrival' => 'Customs Clearance On Arrival',
            'customs_clearance_ondeparture' => 'Customs Clearance On Departure',
            'hoses_connected' => 'Hoses Connected',
            'pre_discharge_safety' => 'Pre Discharge Saftey Meeting',
            'surveyor_on_board' => 'Surveyor On Board',
            'sampling' => 'Sampling',
            'tank_inspection_completed' => 'Tank Inspection Completed',
            'hoses_disconnected' => 'Hoses Disconnected',
            'sbe' => 'SBE',
            'documentation_completed' => 'Documentation Completed',
            'cast_off' => 'Cast Off',
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
