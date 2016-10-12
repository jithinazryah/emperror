<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property string $branch_name
 * @property string $branch_code
 * @property string $responisible_peerson
 * @property string $phone1
 * @property string $phone2
 * @property string $email
 * @property string $address
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Branch extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'branch';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                    [ ['branch_name', 'branch_code', 'phone1', 'email', 'CB', 'UB'], 'required'],
                    [ ['address'], 'string'],
                    [ ['status', 'CB', 'UB'], 'integer'],
                    [ ['DOC', 'DOU'], 'safe'],
                    [ ['branch_name', 'responisible_peerson'], 'string', 'max' => 100],
                    [ ['branch_code', 'phone1', 'phone2'], 'string', 'max' => 15],
                    [ ['email'], 'string', 'max' => 50],
                    [ ['email'], 'email'],
                    [ ['email'], 'unique'],
                    [ ['branch_name', 'branch_code', 'responisible_peerson', 'phone1', 'status'], 'required', 'on' => 'create'],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'branch_name' => 'Branch Name',
                    'branch_code' => 'Branch Code',
                    'responisible_peerson' => 'Responisible Peerson',
                    'phone1' => 'Phone1',
                    'phone2' => 'Phone2',
                    'email' => 'Email',
                    'address' => 'Address',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

}
