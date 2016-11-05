<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "close_estimate_sub_service".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $close_estimate_id
 * @property integer $service_id
 * @property integer $sub_service
 * @property double $unit
 * @property double $unit_price
 * @property double $total
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class CloseEstimateSubService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'close_estimate_sub_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id', 'close_estimate_id', 'CB', 'UB', 'DOC'], 'required'],
            [['appointment_id', 'close_estimate_id', 'service_id', 'sub_service', 'status', 'CB', 'UB'], 'integer'],
            [['unit', 'unit_price', 'total'], 'number'],
            [['comments'], 'string'],
            [['DOC', 'DOU'], 'safe'],
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
            'close_estimate_id' => 'Close Estimate ID',
            'service_id' => 'Service ID',
            'sub_service' => 'Sub Service',
            'unit' => 'Unit',
            'unit_price' => 'Unit Price',
            'total' => 'Total',
            'comments' => 'Comments',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
    public function getService() {
                return $this->hasOne(Services::className(), ['id' => 'service_id']);
        }
        
        public function getSub() {
                return $this->hasOne(MasterSubService::className(), ['id' => 'sub_service']);
        }
}
