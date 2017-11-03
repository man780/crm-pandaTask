<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_profile".
 *
 * @property int $task_id
 * @property int $profile_id
 * @property string $created_at
 *
 * @property Profile $profile
 * @property Task $task
 */
class TaskProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'profile_id'], 'required'],
            [['task_id', 'profile_id'], 'integer'],
            [['created_at'], 'safe'],
            [['task_id', 'profile_id'], 'unique', 'targetAttribute' => ['task_id', 'profile_id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'user_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => Yii::t('app', 'Task ID'),
            'profile_id' => Yii::t('app', 'Profile ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'profile_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
