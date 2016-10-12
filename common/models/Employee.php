<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use common\models\Branch;

/**
 * This is the model class for table "employee".
 *
 * @property integer $id
 * @property integer $post_id
 * @property string $branch_id
 * @property string $user_name
 * @property string $employee_code
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property integer $gender
 * @property integer $maritual_status
 * @property string $address
 * @property string $date_of_join
 * @property integer $salary_package
 * @property string $photo
 * @property integer $status
 * @property integer $CB
 * @property integer $UB
 * @property string $DOC
 * @property string $DOU
 *
 * @property AdminPosts $post
 */
class Employee extends ActiveRecord implements IdentityInterface {

        private $_user;
        public $rememberMe = true;
        public $created_at;
        public $updated_at;

        /**
         * @inheritdoc
         */
        public static function tableName() {
                return 'employee';
        }

        /**
         * @inheritdoc
         */
        public function rules() {
                return [
                    [['post_id', 'user_name', 'branch_id', 'employee_code', 'password', 'name', 'email', 'phone', 'gender', 'maritual_status'], 'required', 'on' => 'create'],
                    [['user_name'], 'unique', 'message' => 'Username must be unique.', 'on' => 'create'],
                    [['email'], 'email'],
                    [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
                    [ ['post_id', 'gender', 'maritual_status', 'salary_package', 'status', 'CB', 'UB'], 'integer'],
                    [ ['address'], 'string'],
                    [ ['date_of_join', 'DOC', 'DOU'], 'safe'],
                    [ ['employee_code', 'name', 'email'], 'string', 'max' => 100],
                    [ ['user_name'], 'string', 'max' => 30],
                    [ ['password'], 'string', 'max' => 300],
                    [ ['phone'], 'string', 'max' => 15],
                    [ ['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdminPosts::className(), 'targetAttribute' => ['post_id' => 'id']],
                    [ ['user_name', 'password'], 'required', 'on' => 'login'],
                    [ ['password'], 'validatePassword', 'on' => 'login'],
                ];
        }

        public function validatePassword($attribute, $params) {
                if (!$this->hasErrors()) {
                        $user = $this->getUser();
                        if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
                                $this->addError($attribute, 'Incorrect username or password.');
                        }
                }
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels() {
                return [
                    'id' => 'ID',
                    'post_id' => 'Post ID',
                    'branch_id' => 'Branch ID',
                    'user_name' => 'User Name',
                    'employee_code' => 'Employee Code',
                    'password' => 'Password',
                    'name' => 'Name',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'gender' => 'Gender',
                    'maritual_status' => 'Maritual Status',
                    'address' => 'Address',
                    'date_of_join' => 'Date Of Join',
                    'salary_package' => 'Salary Package',
                    'photo' => 'Photo',
                    'status' => 'Status',
                    'CB' => 'Cb',
                    'UB' => 'Ub',
                    'DOC' => 'Doc',
                    'DOU' => 'Dou',
                ];
        }

        public function login() {
                if ($this->validate()) {
                        return Yii::$app->user->login($this->getUser(), /* $this->rememberMe ? 3600 * 24 * 30 : */ 0);
                } else {
                        return false;
                }
        }

        protected function getUser() {
                if ($this->_user === null) {
                        $this->_user = static::find()->where('user_name = :uname and status = :stat', ['uname' => $this->user_name, 'stat' => '1'])->one();
                }

                return $this->_user;
        }

        public function validatedata($data) {
                if ($data == '') {
                        $this->addError('password', 'Incorrect username or password');
                        return true;
                }
        }

        /**
         * Finds user by username
         *
         * @param string $username
         * @return static|null
         */
        public static function findByUsername($username) {
                return static::findOne(['user_name' => $username, 'status' => 1]);
        }

        /**
         * @inheritdoc
         */
        public static function findIdentity($id) {
                return static::findOne(['id' => $id, 'status' => 1]);
        }

        /**
         * @inheritdoc
         */
        public static function findIdentityByAccessToken($token, $type = null) {
                throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        }

        public function getId() {
                return $this->getPrimaryKey();
        }

        /**
         * @inheritdoc
         */
        public function getAuthKey() {
                return $this->auth_key;
        }

        /**
         * @inheritdoc
         */
        public function validateAuthKey($authKey) {
                return $this->getAuthKey() === $authKey;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getPost() {
                return $this->hasOne(AdminPosts::className(), ['id' => 'post_id']);
        }

        public function getBranchName($branch_id) {
                $data = Branch::findOne(['id' => $branch_id]);
                return $data->branch_name;
        }

}
