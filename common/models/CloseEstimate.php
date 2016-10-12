<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "close_estimate".
 *
 * @property integer $id
 * @property integer $apponitment_id
 * @property integer $service_id
 * @property integer $supplier
 * @property integer $currency
 * @property string $unit_rate
 * @property string $unit
 * @property string $roe
 * @property integer $epda
 * @property integer $fda
 * @property string $payment_type
 * @property integer $total
 * @property integer $invoice_type
 * @property integer $principal
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class CloseEstimate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'close_estimate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apponitment_id', 'service_id', 'CB', 'UB'], 'required'],
            [['apponitment_id', 'service_id', 'supplier', 'currency', 'epda', 'fda', 'total', 'invoice_type', 'principal', 'status', 'CB', 'UB'], 'integer'],
            [['comments'], 'string'],
            [['DOC', 'DOU'], 'safe'],
            [['unit_rate', 'unit'], 'string', 'max' => 50],
            [['roe', 'payment_type'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apponitment_id' => 'Apponitment ID',
            'service_id' => 'Service ID',
            'supplier' => 'Supplier',
            'currency' => 'Currency',
            'unit_rate' => 'Unit Rate',
            'unit' => 'Unit',
            'roe' => 'Roe',
            'epda' => 'Epda',
            'fda' => 'Fda',
            'payment_type' => 'Payment Type',
            'total' => 'Total',
            'invoice_type' => 'Invoice Type',
            'principal' => 'Principal',
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
        
        /**
         * @return \yii\db\ActiveQuery 
         */
        public function getSupplier0() {
                return $this->hasOne(Contacts::className(), ['id' => 'supplier']);
        }
        /**
         * @return \yii\db\ActiveQuery 
         */
        public function getPrincipal0() {
                return $this->hasOne(Debtor::className(), ['id' => 'principal']);
        }
        
        public function getCurrency0() {
                return $this->hasOne(Currency::className(), ['id' => 'currency']);
        }
        
        public function getInvoice() {
                return $this->hasOne(InvoiceType::className(), ['id' => 'invoice_type']);
        }
}
