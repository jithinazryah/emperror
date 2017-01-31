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
                        [['invoice', 'on_account_of', 'job', 'payment_terms', 'status', 'CB', 'UB'], 'integer'],
                        [['to_address'], 'string'],
                        [['date', 'DOC', 'DOU'], 'safe'],
//            [['CB', 'UB', 'DOC'], 'required'],
                    [['invoice_number', 'oops_id', 'doc_no', 'cheque_no'], 'string', 'max' => 100],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'invoice' => 'Invoice',
                    'to_address' => 'To Address',
                    'invoice_number' => 'Invoice Number',
                    'date' => 'Date',
                    'oops_id' => 'Oops ID',
                    'on_account_of' => 'On Account Of',
                    'job' => 'Job',
                    'payment_terms' => 'Payment Terms',
                    'doc_no' => 'Doc No',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

}