<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "on_account".
 *
 * @property integer $id
 * @property string $date
 * @property integer $transaction_type
 * @property integer $payment_type
 * @property string $check_no
 * @property double $amount
 * @property double $balance
 * @property integer $appointment_id
 * @property string $comment
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class OnAccount extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'on_account';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['date', 'DOC', 'DOU', 'CB', 'UB'], 'safe'],
                        [['transaction_type', 'payment_type', 'appointment_id', 'status', 'CB', 'UB', 'debtor_id'], 'integer'],
                        [['amount', 'balance'], 'number'],
                        [['comment'], 'string'],
                        [['check_no'], 'string', 'max' => 50],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'date' => 'Date',
                    'transaction_type' => 'Transaction Type',
                    'payment_type' => 'Payment Type',
                    'check_no' => 'Check No',
                    'amount' => 'Amount',
                    'balance' => 'Balance',
                    'appointment_id' => 'Appointment ID',
                    'comment' => 'Comment',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

}
