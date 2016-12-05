<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "supplier_funding".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $close_estimate_id
 * @property integer $service_id
 * @property integer $supplier
 * @property double $actual_amount
 * @property double $amount_debit
 * @property double $balance_amount
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class SupplierFunding extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_funding';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appointment_id', 'close_estimate_id', 'service_id', 'supplier', 'status', 'CB', 'UB'], 'integer'],
            [['actual_amount', 'amount_debit', 'balance_amount'], 'number'],
            [['CB', 'UB', 'DOC'], 'required'],
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
            'supplier' => 'Supplier',
            'actual_amount' => 'Actual Amount',
            'amount_debit' => 'Amount Debit',
            'balance_amount' => 'Balance Amount',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
