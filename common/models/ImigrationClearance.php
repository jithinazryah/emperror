<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "imigration_clearance".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property string $arrived_ps
 * @property string $pob_inbound
 * @property string $first_line_ashore
 * @property string $all_fast
 * @property string $agent_on_board
 * @property string $imi_clearence_commenced
 * @property string $imi_clearence_completed
 * @property string $pob_outbound
 * @property string $cast_off
 * @property string $last_line_away
 * @property string $cleared_break_water
 * @property string $drop_anchor
 * @property string $heave_up_anchor
 * @property string $pilot_boarded
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class ImigrationClearance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imigration_clearance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id'], 'required'],
            [['appointment_id', 'status', 'CB', 'UB'], 'integer'],
            [['arrived_ps', 'pob_inbound', 'first_line_ashore', 'all_fast', 'agent_on_board', 'imi_clearence_commenced', 'imi_clearence_completed', 'pob_outbound', 'cast_off', 'last_line_away', 'cleared_break_water', 'drop_anchor', 'heave_up_anchor', 'pilot_boarded', 'DOC', 'DOU'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appointment_id' => 'Appointment ID',
            'arrived_ps' => 'Arrived Ps',
            'pob_inbound' => 'POB(inbound)',
            'first_line_ashore' => 'First Line Ashore',
            'all_fast' => 'All Fast',
            'agent_on_board' => 'Agent On Board',
            'imi_clearence_commenced' => 'Imi Clearence Commenced',
            'imi_clearence_completed' => 'Imi Clearence Completed',
            'pob_outbound' => 'POB(outbound)',
            'cast_off' => 'Cast Off',
            'last_line_away' => 'Last Line Away',
            'cleared_break_water' => 'Cleared Break Water',
            'drop_anchor' => 'Drop Anchor(if any)',
            'heave_up_anchor' => 'Heave Up Anchor(if any)',
            'pilot_boarded' => 'Pilot Boarded(if applicable)',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
