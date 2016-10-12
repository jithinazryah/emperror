<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_categorys".
 *
 * @property integer $id
 * @property string $category_name
 * @property string $invoice_type
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class ServiceCategorys extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_categorys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_name', 'invoice_type'], 'required'],
            [['status', 'CB', 'UB'], 'integer'],
            [['DOC', 'DOU','sort_order'], 'safe'],
            [['category_name'], 'string', 'max' => 200],
           // [['invoice_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'invoice_type' => 'Invoice Type',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
    
     public function getInvoiceType0() {
                return $this->hasOne(InvoiceType::className(), ['id' => 'invoice_type']);
        }
}
