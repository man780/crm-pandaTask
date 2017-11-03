<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string $avatar
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property int $birthday
 * @property int $gender
 * @property int $branch_id
 * @property string $skype
 * @property string $phone
 * @property string $telegramm
 *
 * @property Execution[] $executions
 * @property Branch $branch
 * @property User $user
 * @property TaskProfile[] $taskProfiles
 * @property Task[] $tasks
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender', 'branch_id'], 'integer'],
            [['avatar'], 'string', 'max' => 255],
            [['first_name', 'second_name', 'middle_name', 'skype', 'phone', 'telegramm'], 'string', 'max' => 32],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /*public function rules()
    {
        return [
            [['birthday', 'gender'], 'integer'],
            [['avatar'], 'string', 'max' => 255],
            [['first_name', 'second_name', 'middle_name'], 'string', 'max' => 32]
        ];
    }*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'avatar' => 'Аватар',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'birthday' => 'Дата рождения',
            'gender' => 'Пол',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function updateProfile()
    {
        $profile = ($profile = Profile::findOne(Yii::$app->user->id)) ? $profile : new Profile();
        $profile->user_id = Yii::$app->user->id;
        $profile->first_name = $this->first_name;
        $profile->second_name = $this->second_name;
        $profile->middle_name = $this->middle_name;
        $profile->birthday = $this->birthday;
        $profile->gender = $this->gender;
        $profile->branch_id = $this->branch_id;
        $profile->skype = $this->skype;
        $profile->phone = $this->phone;
        $profile->telegramm = $this->telegramm;
        $profile->avatar = $this->avatar;
        //var_dump($profile->attributes); die;
        if($profile->save()):

            $user = $this->user ? $this->user : User::findOne(Yii::$app->user->id);
            $username = Yii::$app->request->post('User')['username'];
            $user->username = isset($username) ? $username : $user->username;
            return $user->save() ? true : false;
        endif;
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutions()
    {
        return $this->hasMany(Execution::className(), ['profile_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskProfiles()
    {
        return $this->hasMany(TaskProfile::className(), ['profile_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->viaTable('task_profile', ['profile_id' => 'user_id']);
    }
}
