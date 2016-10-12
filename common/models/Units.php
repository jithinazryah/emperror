<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "units".
 *
 * @property integer $id
 * @property string $unit_name
 * @property string $unit_symbol
 * @property string $base_unit
 * @property string $unit_relation
 * @property string $comment
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Units extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['status', 'CB', 'UB'], 'integer'],
            [['CB', 'UB'], 'required'],
            [['DOC', 'DOU'], 'safe'],
            [['unit_name', 'unit_symbol', 'base_unit'], 'string', 'max' => 100],
            [['unit_relation'], 'string', 'max' => 225],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit_name' => 'Unit Name',
            'unit_symbol' => 'Unit Symbol',
            'base_unit' => 'Base Unit',
            'unit_relation' => 'Unit Relation',
            'comment' => 'Comment',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
