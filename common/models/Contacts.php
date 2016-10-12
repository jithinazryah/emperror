<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property integer $id
 * @property string $name
 * @property string $person
 * @property string $email
 * @property string $phone_1
 * @property string $phone_2
 * @property string $address
 * @property string $comment
 * @property string $contact_type
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Contacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'person', 'email', 'phone_1', 'contact_type'], 'required'],
            [['address', 'comment'], 'string'],
            [['email'], 'email'],
            [['status', 'CB', 'UB'], 'integer'],
            [['DOC', 'DOU'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['person', 'email'], 'string', 'max' => 100],
            [['phone_1', 'phone_2'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'person' => 'Person',
            'email' => 'Email',
            'phone_1' => 'Phone Primary',
            'phone_2' => 'Phone Secondary',
            'address' => 'Address',
            'comment' => 'Comment',
            'contact_type' => 'Contact Type',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
    public function getContactName($contacts_id) {
                if($contacts_id == 1){
                        return 'Nominator';
                }
                elseif($contacts_id == 2){
                        return 'Charterer';
                }
                elseif($contacts_id == 3){
                        return 'Shipper';
                }elseif($contacts_id == 4){
                        return 'Supplier';
                }
        }
}
