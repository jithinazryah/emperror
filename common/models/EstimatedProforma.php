<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "estimated_proforma".
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
 * @property integer $principal
 * @property integer $invoice_type
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class EstimatedProforma extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'estimated_proforma';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                    [['apponitment_id', 'service_id'], 'required'],
                    [['apponitment_id', 'service_id', 'supplier', 'currency','service_category', 'invoice_type', 'status', 'CB', 'UB'], 'integer'],
                    [['comments', 'principal'], 'string'],
                    [['DOC', 'DOU', 'epda'], 'safe'],
                    [['unit_rate', 'unit'], 'string', 'max' => 50],
                    [['roe'], 'string', 'max' => 15],
                    [['images'], 'file', 'extensions' => 'pdf', 'maxFiles' => 4],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'apponitment_id' => 'Apponitment ID',
                    'service_id' => 'Service ID',
                    'service_category' => 'Service Category',
                    'supplier' => 'Supplier',
                    'currency' => 'Currency',
                    'unit_rate' => 'Unit Rate',
                    'unit' => 'Unit',
                    'roe' => 'Roe',
                    'epda' => 'Epda',
                    'principal' => 'Principal',
                    'invoice_type' => 'Invoice Type',
                    'comments' => 'Comments',
                    'images' => 'Images',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

        /**
         * @return \yii\db\ActiveQuery 
         */
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


}
