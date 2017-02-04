<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "generate_invoice".
 *
 * @property integer $id
 * @property integer $invoice
 * @property string $to_address
 * @property string $invoice_number
 * @property string $date
 * @property string $oops_id
 * @property integer $on_account_of
 * @property integer $job
 * @property integer $payment_terms
 * @property string $doc_no
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class GenerateInvoice extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'generate_invoice';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['on_account_of', 'to_address', 'job'], 'required'],
                        [['invoice', 'on_account_of', 'job', 'payment_terms', 'status', 'CB', 'UB', 'supplier'], 'integer'],
                        [['to_address'], 'string'],
                        [['date', 'DOC', 'DOU', 'oops_id', 'currency', 'customer_code', 'remarks', 'bank_details'], 'safe'],
//            [['CB', 'UB', 'DOC'], 'required'],
                    [['invoice_number', 'doc_no', 'cheque_no'], 'string', 'max' => 100],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'invoice' => 'Debtor',
                    'supplier' => 'Contacts',
                    'to_address' => 'To Address',
                    'invoice_number' => 'Invoice Number',
                    'date' => 'Date',
                    'oops_id' => 'Ops ID',
                    'on_account_of' => 'On Account Of',
                    'job' => 'Job',
                    'payment_terms' => 'Payment Terms',
                    'doc_no' => 'Doc No',
                    'currency' => 'Currency',
                    'customer_code' => 'Customer Code',
                    'remarks' => 'Remarks',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

}
