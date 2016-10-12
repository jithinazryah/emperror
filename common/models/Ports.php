<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ports".
 *
 * @property integer $id
 * @property string $port_name
 * @property string $code
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Ports extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['port_name', 'code'], 'required'],
            [['status', 'CB', 'UB'], 'integer'],
            [['DOC', 'DOU'], 'safe'],
            [['port_name', 'code'], 'string', 'max' => 200],
            [['port_name', 'code'], 'required', 'on' => 'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'port_name' => 'Port Name',
            'code' => 'Code',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
