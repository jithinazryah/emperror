<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purpose".
 *
 * @property integer $id
 * @property string $purpose
 * @property integer $time_required
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Purpose extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purpose';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purpose'], 'required'],
            [['time_required', 'status', 'CB', 'UB'], 'integer'],
            [['DOC', 'DOU'], 'safe'],
            [['purpose'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purpose' => 'Purpose',
            'time_required' => 'Time Required',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
