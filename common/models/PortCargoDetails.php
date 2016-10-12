<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_cargo_details".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $port_call_id
 * @property string $cargo_type
 * @property string $loaded_quantity
 * @property string $bl_quantity
 * @property string $remarks
 * @property string $stoppages_delays
 * @property string $cargo_document
 * @property string $masters_comment
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortCargoDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'port_cargo_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id', 'port_call_id'], 'required'],
            [['appointment_id', 'port_call_id', 'CB', 'UB'], 'integer'],
            [['cargo_type', 'remarks', 'cargo_document', 'masters_comment'], 'string'],
            [['DOC', 'DOU', 'remarks', 'stoppages_delays', 'cargo_document', 'masters_comment', 'CB', 'UB', 'cargo_type', 'loaded_quantity', 'bl_quantity'], 'safe'],
            [['loaded_quantity', 'bl_quantity'], 'string', 'max' => 300],
            [['stoppages_delays'], 'string', 'max' => 500],
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
            'port_call_id' => 'Port Call ID',
            'cargo_type' => 'Cargo Type',
            'loaded_quantity' => 'Loaded Quantity',
            'bl_quantity' => 'Bl Quantity',
            'remarks' => 'Remarks',
            'stoppages_delays' => 'Stoppages Delays',
            'cargo_document' => 'Cargo Document',
            'masters_comment' => 'Masters Comment',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
