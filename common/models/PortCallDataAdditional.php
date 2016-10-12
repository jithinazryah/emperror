<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_call_data_additional".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $type
 * @property integer $data_id
 * @property string $label
 * @property string $value
 * @property string $comment
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortCallDataAdditional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'port_call_data_additional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id', 'CB', 'UB'], 'required'],
            [['appointment_id', 'type', 'data_id', 'status', 'CB', 'UB'], 'integer'],
            [['value', 'DOC', 'DOU'], 'safe'],
            [['comment'], 'string'],
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
            'data_id' => 'Data ID',
            'label' => 'Label',
            'value' => 'Value',
            'comment' => 'Comment',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
