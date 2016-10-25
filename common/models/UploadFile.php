<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class UploadFile extends Model {

        public $filee;
        public $appointment_id;
        public $type;
        public $type_id;

        public function rules() {
                return [
                    [['filee', 'appointment_id'], 'required'],
                    [['appointment_id', 'type_id',], 'integer'],
                    [['type'], 'string', 'max' => 200],
//                    [['filee'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf','txt'],
                    [['filee'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf,txt,doc,docx,xls,xlsx,msg, jpg, jpeg, png,', 'maxFiles' => 4],
                ];
        }

        public function attributeLabels() {
                return [
                    'filee' => 'Choose a file to Upload',
                    
                ];
        }

}
