<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "uploads".
 *
 * @property integer $id
 * @property string $form_name
 * @property string $comment
 * @property string $upload_file
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 */
class Uploads extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uploads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['status', 'CB', 'UB'], 'integer'],
            [['CB', 'UB','DOC', 'DOU'], 'safe'],
            [['form_name', 'upload_file'], 'string', 'max' => 200],
            [['upload_file'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'pdf,txt,doc,docx,xls,xlsx,msg, jpg, jpeg, png,', 'on' => 'create'],
            [['upload_file'], 'file', 'skipOnEmpty' => FALSE, 'extensions' => 'pdf,txt,doc,docx,xls,xlsx,msg, jpg, jpeg, png,', 'on' => 'update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_name' => 'Form Name',
            'comment' => 'Comment',
            'upload_file' => 'Upload File',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }
}
