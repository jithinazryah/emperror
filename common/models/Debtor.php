<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "debtor".
 *
 * @property integer $id
 * @property string $principal_name
 * @property string $address
 * @property string $mobile
 * @property string $tele_phone
 * @property string $fax
 * @property string $invoicing_address
 * @property string $da_dispatch_addresss_1
 * @property string $da_dispatch_addresss_2
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Debtor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'debtor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['principal_name', 'address', 'mobile'], 'required'],
            [['address', 'invoicing_address', 'da_dispatch_addresss_1', 'da_dispatch_addresss_2'], 'string'],
            [['status', 'CB', 'UB'], 'integer'],
            [['DOC', 'DOU'], 'safe'],
            [['principal_name', 'principal_id'], 'string', 'max' => 100],
            [['mobile', 'tele_phone', 'fax'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'principal_name' => 'Principal Name',
            'principal_id' => 'Principal ID',
            'address' => 'Address',
            'mobile' => 'Mobile',
            'tele_phone' => 'Tele Phone',
            'fax' => 'Fax',
            'invoicing_address' => 'Invoicing Address',
            'da_dispatch_addresss_1' => 'Da Dispatch Addresss 1',
            'da_dispatch_addresss_2' => 'Da Dispatch Addresss 2',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
