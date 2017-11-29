<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $avatar
 * @property string $name
 * @property int $birthday
 * @property int $branch_id
 * @property string $skype
 * @property string $phone
 * @property string $telegramm
 * @property string $password_hash
 * @property int $status
 * @property int $role
 * @property string $auth_key
 * @property string $secret_key
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Execution[] $executions
 * @property TaskUser[] $taskUsers
 * @property Task[] $tasks
 * @property Branch $branch
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
	const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;
    public $password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'filter', 'filter' => 'trim'],
            [['email', 'status'], 'required'],
            ['email', 'email'],
            ['password', 'required', 'on' => 'create'],
            ['email', 'unique', 'message' => 'Эта почта уже зарегистрирована.'],
            ['secret_key', 'unique'],

            //[['username', 'email', 'password_hash', 'status', 'auth_key', 'created_at', 'updated_at'], 'required'],
            /*[['branch_id', 'status', 'role'], 'integer'],
            [['username', 'email', 'avatar', 'password_hash', 'secret_key'], 'string', 'max' => 255],
            [['name', 'skype', 'phone', 'telegramm', 'auth_key'], 'string', 'max' => 32],*/
            [
                ['branch_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Branch::className(),
                'targetAttribute' => ['branch_id' => 'id']
            ],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
		return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'avatar' => Yii::t('app', 'Avatar'),
            'name' => Yii::t('app', 'Name'),
            'birthday' => Yii::t('app', 'Birthday'),
            'branch_id' => Yii::t('app', 'Branch ID'),
            'skype' => Yii::t('app', 'Skype'),
            'phone' => Yii::t('app', 'Phone'),
            'telegramm' => Yii::t('app', 'Telegramm'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'status' => Yii::t('app', 'Status'),
            'role' => Yii::t('app', 'Role'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'secret_key' => Yii::t('app', 'Secret Key'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function updateUser()
    {
        //vd($this);
        $profile = ($profile = User::findOne(Yii::$app->user->id)) ? $profile : new User();
        $profile->name = $this->name;
        $profile->birthday = $this->birthday;
        $profile->branch_id = $this->branch_id;
        $profile->skype = $this->skype;
        $profile->phone = $this->phone;
        $profile->telegramm = $this->telegramm;
        $profile->avatar = $this->avatar;
        //var_dump($profile->attributes); die;
        return $profile->save();



    }
    
	/* ����� */
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutions()
    {
        return $this->hasMany(Execution::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskUsers()
    {
        return $this->hasMany(TaskUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->
        orderBy(['id' => SORT_DESC])->viaTable('task_user', ['user_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getTaskUsers()
    {
        return $this->hasMany(TaskUser::className(), ['user_id' => 'id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->
            orderBy(['id' => SORT_DESC])->viaTable('task_user', ['user_id' => 'id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }
	
	/* ��������� */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }
    /* ����� */
    /** ������� ������������ �� ����� � ���������� ������ ���������� ������������.
     *  ����������� �� ������ LoginForm.
     */
    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username
        ]);
    }
    /* ������� ������������ �� ����� */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email
        ]);
    }
    public static function findBySecretKey($key)
    {
        if (!static::isSecretKeyExpire($key))
        {
            return null;
        }
        return static::findOne([
            'secret_key' => $key,
        ]);
    }
    /* ������� */
    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString().'_'.time();
    }
    public function removeSecretKey()
    {
        $this->secret_key = null;
    }
    public static function isSecretKeyExpire($key)
    {
        if (empty($key))
        {
            return false;
        }
        $expire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    /**
     * ���������� ��� �� ���������� ������ � ����������� (��� ������) ���������� �������� ���� password_hash ������� user ���
     * ������ ������������.
     * ����������� �� ������ RegForm.
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * ���������� ��������� ������ �� 32 ����������������� �������� � ����������� (��� ������) ���������� �������� ���� auth_key
     * ������� user ��� ������ ������������.
     * ����������� �� ������ RegForm.
     */
    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * ���������� ���������� ������ � ������� � ���� password_hash, ��� �������� ������������, � ������� user.
     * ����������� �� ������ LoginForm.
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /* �������������� ������������� */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE
        ]);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    /*public static function findAvatar()
    {
        return 1;//$this->profile->avatar;
    }*/

    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    
    
}
