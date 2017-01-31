<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "delivery_order".
 *
 * @property integer $id
 * @property string $to
 * @property string $ref_no
 * @property string $date
 * @property string $name
 * @property string $po_box
 * @property string $arrived_from
 * @property string $arrived_on
 * @property string $vessel_name
 * @property string $voyage_no
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class DeliveryOrder extends \yii\db\ActiveRecord {

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'delivery_order';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                        [['to', 'name', 'arrived_on', 'arrived_from', 'po_box', 'vessel_name', 'voyage_no'], 'required'],
                        [['to'], 'string'],
                        [['date', 'arrived_on', 'DOC', 'DOU'], 'safe'],
                        [['status', 'CB', 'UB'], 'integer'],
                        [['CB', 'UB', 'DOC'], 'required'],
                        [['ref_no', 'name', 'po_box', 'vessel_name', 'voyage_no'], 'string', 'max' => 100],
                        [['arrived_from'], 'string', 'max' => 200],
                ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'to' => 'To',
                    'ref_no' => 'Ref No',
                    'date' => 'Date',
                    'name' => 'Name',
                    'po_box' => 'Po Box',
                    'arrived_from' => 'Arrived From',
                    'arrived_on' => 'Arrived On',
                    'vessel_name' => 'Vessel Name',
                    'voyage_no' => 'Voyage No',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

}
