<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_posts".
 *
 * @property integer $id
 * @property string $post_name
 * @property integer $admin
 * @property integer $masters
 * @property integer $appointments
 * @property integer $estimated_proforma
 * @property integer $port_call_data
 * @property integer $close_estimate
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 *
 * @property Employee[] $employees
 */
class AdminPosts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_name'], 'required'],
            [['admin', 'masters', 'appointments', 'estimated_proforma', 'port_call_data', 'close_estimate', 'status', 'CB', 'UB'], 'integer'],
            [['DOC', 'DOU'], 'safe'],
            [['post_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_name' => 'Post Name',
            'admin' => 'Admin',
            'masters' => 'Masters',
            'appointments' => 'Appointments',
            'estimated_proforma' => 'Estimated Proforma',
            'port_call_data' => 'Port Call Data',
            'close_estimate' => 'Close Estimate',
            'status' => 'Status',
            'CB' => 'Cb',
            'UB' => 'Ub',
            'DOC' => 'Doc',
            'DOU' => 'Dou',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['post_id' => 'id']);
    }
}
