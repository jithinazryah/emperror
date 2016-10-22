<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "test".
 *
 * @property integer $id
 * @property string $eta
 * @property string $ets
 * @property string $esop
 * @property string $nor_tenderd
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eta', 'ets', 'esop', 'nor_tenderd'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eta' => 'Eta',
            'ets' => 'Ets',
            'esop' => 'Esop',
            'nor_tenderd' => 'Nor Tenderd',
        ];
    }
}
