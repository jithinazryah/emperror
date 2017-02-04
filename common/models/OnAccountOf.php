<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "on_account_of".
 *
 * @property integer $id
 * @property string $on_account_of
 * @property string $invoice_name
 * @property string $code
 */
class OnAccountOf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'on_account_of';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['on_account_of', 'invoice_name', 'code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'on_account_of' => 'On Account Of',
            'invoice_name' => 'Invoice Name',
            'code' => 'Code',
        ];
    }
}
