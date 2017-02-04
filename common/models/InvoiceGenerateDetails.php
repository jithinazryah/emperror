<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_generate_details".
 *
 * @property integer $id
 * @property string $description
 * @property string $qty
 * @property string $unit_price
 * @property string $Total
 */
class InvoiceGenerateDetails extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'invoice_generate_details';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['description'], 'string'],
                        [['qty', 'unit_price', 'total'], 'number'],
                        [['invoice_id', 'status', 'CB', 'UB', 'DOC', 'DOU', 'comments'], 'safe'],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'description' => 'Description',
                    'qty' => 'Qty',
                    'unit_price' => 'Unit Price',
                    'total' => 'Total',
                    'comments' => 'Comments',
                ];
        }

}
