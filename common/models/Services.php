<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $service
 * @property integer $invocie_type
 * @property integer $supplier
 * @property integer $unit_rate
 * @property integer $unit
 * @property integer $currency
 * @property string $roe
 * @property integer $epda_value
 * @property integer $cost_allocation
 * @property string $comments
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Services extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'services';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                    [['category_id', 'service', 'invocie_type'], 'required'],
                    [['category_id', 'invocie_type', 'unit_rate', 'unit', 'currency', 'epda_value', 'cost_allocation', 'status', 'CB', 'UB'], 'integer'],
                    [['comments'], 'string'],
                    [['DOC', 'DOU', 'supplier_options', 'supplier'], 'safe'],
                    [['service'], 'string', 'max' => 200],
                    [['roe'], 'string', 'max' => 100],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'category_id' => 'Category ID',
                    'service' => 'Service',
                    'invocie_type' => 'Invocie Type',
                    'supplier_options' => 'Supplier Options',
                    'supplier' => 'Supplier',
                    'unit_rate' => 'Unit Rate',
                    'unit' => 'Unit',
                    'currency' => 'Currency',
                    'roe' => 'Roe',
                    'epda_value' => 'Epda Value',
                    'cost_allocation' => 'Cost Allocation',
                    'comments' => 'Comments',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

        public function getSupplierName($supplier_id) {
                return Contacts::findOne(['id' => $supplier_id])->name;
        }

        public function getInvoicetype0() {
                return $this->hasOne(InvoiceType::className(), ['id' => 'invocie_type']);
        }

        public function getCategory() {
                return $this->hasOne(ServiceCategorys::className(), ['id' => 'category_id']);
        }

        public function getSupplier0() {
                return $this->hasOne(Contacts::className(), ['id' => 'supplier']);
        }

        public function getUnit0() {
                return $this->hasOne(Units::className(), ['id' => 'unit']);
        }

        public function getCurrency0() {
                return $this->hasOne(Currency::className(), ['id' => 'currency']);
        }
        public function getInvoiceName($invocie_type) {
                return InvoiceType::findOne(['id' => $invocie_type])->invoice_type;
        }

}
