<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vessel".
 *
 * @property integer $id
 * @property integer $vessel_type
 * @property string $vessel_name
 * @property string $imo_no
 * @property string $official
 * @property string $mmsi_no
 * @property string $owners_info
 * @property string $mobile
 * @property string $land_line
 * @property string $direct_line
 * @property string $fax
 * @property string $picture
 * @property string $dwt
 * @property string $grt
 * @property string $nrt
 * @property string $loa
 * @property string $beam
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Vessel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vessel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vessel_type', 'vessel_name'], 'required'],
            [['imo_no', 'vessel_name', 'official'], 'unique'],
            [['vessel_type', 'status', 'CB', 'UB'], 'integer'],
            [['owners_info'], 'string'],
            [['DOC', 'DOU'], 'safe'],
            [['vessel_name'], 'string', 'max' => 200],
            [['imo_no', 'official', 'mmsi_no', 'picture'], 'string', 'max' => 100],
            [['mobile', 'land_line', 'direct_line', 'fax', 'dwt', 'grt', 'nrt', 'loa', 'beam'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vessel_type' => 'Vessel Type',
            'vessel_name' => 'Vessel Name',
            'imo_no' => 'Imo No',
            'official' => 'Official',
            'mmsi_no' => 'Flag',
            'owners_info' => 'Owners Info',
            'mobile' => 'Mobile',
            'land_line' => 'Land Line',
            'direct_line' => 'Direct Line',
            'fax' => 'Fax',
            'picture' => 'Picture',
            'dwt' => 'DWT',
            'grt' => 'GRT',
            'nrt' => 'NRT',
            'loa' => 'LOA',
            'beam' => 'Beam',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
