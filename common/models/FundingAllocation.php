<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "funding_allocation".
 *
 * @property integer $id
 * @property integer $appointment_id
 * @property integer $principal_id
 * @property string $type
 * @property double $amount
 * @property double $outstanding
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class FundingAllocation extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'funding_allocation';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['appointment_id', 'principal_id', 'status', 'CB', 'UB'], 'integer'],
                        [['amount', 'outstanding'], 'number'],
                        [['CB', 'UB', 'DOC'], 'required'],
                        [['DOC', 'DOU', 'fund_date', 'description'], 'safe'],
                        [['type', 'payment_type', 'check_no'], 'string', 'max' => 50],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'appointment_id' => 'Appointment ID',
                    'principal_id' => 'Principal ID',
                    'description' => 'Description',
                    'type' => 'Type',
                    'payment_type' => 'Payment Type',
                    'check_no' => 'Check Number',
                    'amount' => 'Amount',
                    'outstanding' => 'Outstanding',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

        public function getPrincipal0() {
                return $this->hasOne(Debtor::className(), ['id' => 'principal_id']);
        }

}
