<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_break_timings".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $type
 * @property string $label
 * @property string $value
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortBreakTimings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'port_break_timings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id', 'status', 'CB', 'UB'], 'required'],
            [['appointment_id', 'type', 'status', 'CB', 'UB'], 'integer'],
            [['value', 'DOC', 'DOU'], 'safe'],
            [['label'], 'string', 'max' => 200],
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
            'type' => 'Type',
            'label' => 'Label',
            'value' => 'Value',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
