<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "port_stoppages".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $type
 * @property string $stoppage_from
 * @property string $stoppage_to
 * @property string $comment
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class PortStoppages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'port_stoppages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id', 'status', 'CB', 'UB', 'DOC'], 'required'],
            [['appointment_id', 'type', 'status', 'CB', 'UB'], 'integer'],
            [['stoppage_from', 'stoppage_to', 'DOC', 'DOU'], 'safe'],
            [['comment'], 'string', 'max' => 200],
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
            'stoppage_from' => 'Stoppage From',
            'stoppage_to' => 'Stoppage To',
            'comment' => 'Comment',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
