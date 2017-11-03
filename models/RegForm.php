<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16.10.2017
 * Time: 21:24
 */

namespace app\models;
use yii\base\Model;
use Yii;

class RegForm extends Model
{
    public $role;
    public $username;
    public $email;
    public $password;
    public $name;
    public $phone;
    public $branch_id;
    public $birthday;
    public $avatar;
    public $status;

    public function rules()
    {
        return [
            [['email', 'password'],'filter', 'filter' => 'trim'],
            [['role', 'email', 'password', 'name', 'birthday', 'branch_id'],'required'],
            //['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'string', 'min' => 6, 'max' => 255],
            /*['username', 'unique',
                'targetClass' => User::className(),
                'message' => 'Это имя уже занято.'],*/
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => User::className(),
                'message' => 'Эта почта уже занята.'],
            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            ['status', 'in', 'range' =>[
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE
            ]],
            ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],
        ];
    }

    public function attributeLabels()
    {
		return [
            //'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'avatar' => Yii::t('app', 'Avatar'),
            'name' => Yii::t('app', 'Name'),
            'birthday' => Yii::t('app', 'Birthday'),
            'branch_id' => Yii::t('app', 'Branch ID'),
            'skype' => Yii::t('app', 'Skype'),
            'phone' => Yii::t('app', 'Phone'),
            'telegramm' => Yii::t('app', 'Telegramm'),
            'status' => Yii::t('app', 'Status'),
            'role' => Yii::t('app', 'Role'),
        ];
        /*return [
            'username' => 'Имя пользователя',
            'email' => 'Эл. почта',
            'password' => 'Пароль'
        ];*/
    }

    public function reg()
    {
        $user = new User();
        //$user->username = $this->username;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->birthday = strtotime($this->birthday);
        $user->phone = $this->phone;
        $user->role = $this->role;
        $user->branch_id = $this->branch_id;
        $user->status = $this->status;
        $user->avatar = $this->avatar;
        $user->setPassword($this->password);
        $user->generateAuthKey();
		//echo "<code>";var_dump($user->attributes); die;
        if($this->scenario === 'emailActivation')
            $user->generateSecretKey();
        return $user->save() ? $user : null;
    }
	
    public function sendActivationEmail($user)
    {
		//mail($this->email, 'Активация для '.Yii::$app->name, '11');
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом).'])
            ->setTo($this->email)
            ->setSubject('Активация для '.Yii::$app->name)
            ->send();
    }
}