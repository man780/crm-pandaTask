<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $priority
 * @property int $deadline
 * @property int $to
 * @property int $to_copywriter_type
 * @property int $to_copywriter_scope
 * @property int $to_copywriter_theme
 * @property string $to_copywriter_text
 * @property string $to_copywriter_special
 * @property string $to_translator_languages
 * @property int $to_developer_type
 * @property string $to_developer_status
 * @property int $shown_by_executor
 * @property int $time
 * @property int $status
 *
 * @property Execution[] $executions
 * @property TaskLanguage[] $taskLanguages
 * @property Language[] $languages
 * @property TaskUser[] $taskUsers
 * @property User[] $users
 */
class Task extends \yii\db\ActiveRecord
{

    private $_toArr = [1 => 'КОПИРАЙТЕРУ', 2 => 'ПЕРЕВОДЧИКУ', 3 => 'РАЗРАБОТЧИКУ'];
    private $_type_site = [1 => 'преленд', 2 => 'ленд', 3 => 'блог', 4 => 'сайт', 5 => 'интернет магазин'];
    private $_status_site = [1 => 'верстка с нуля', 2 => 'переделка'];
    private $_priorities = [1=>'низкий', 2=>'средний', 3=>'высокий'];
    private $_copywriter_type = [1=>'статья для блога', 2=>' статья для преленда', 3=>'заголовки', 4 => 'комментарии'];
    private $_copywriter_theme = [1=>'адалт', 2=>' красота и здоровье', 3=>'медицина', 4 => 'бизнес и финансы', 5 => 'гороскопы и эзотерика'];
    //private $_status = [-1=>'Отказ', 0=>'на рассмотрение', 1=>'в процессе', 5=>'на проверке',  15 => 'готовый'];
    private $_statuses = [
        0 => [
            'color' => '#d9534f',
            'name' => 'Задача поставлена',
        ],
        1 => [
            'color' => '#f0ad4e',
            'name' => 'В процессе выполнения',
        ],
        3 => [
            'color' => '#337ab7',
            'name' => 'Требует внимания',
        ],
        5 => [
            'color' => '#5bc0de',
            'name' => 'На проверке',
        ],
        15 => [
            'color' => '#5cb85c',
            'name' => 'Выполнено',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body', 'to_copywriter_text'], 'string'],
            [['priority', 'to', 'to_copywriter_type', 'to_copywriter_scope', 'to_copywriter_theme', 'to_developer_type', 'shown_by_executor', 'status'], 'integer'],
            [['title', 'to_copywriter_special', 'to_translator_languages', 'to_developer_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'priority' => Yii::t('app', 'Priority'),
            'deadline' => Yii::t('app', 'Deadline'),
            'to' => Yii::t('app', 'To'),
            'to_copywriter_type' => Yii::t('app', 'To Copywriter Type'),
            'to_copywriter_scope' => Yii::t('app', 'To Copywriter Scope'),
            'to_copywriter_theme' => Yii::t('app', 'To Copywriter Theme'),
            'to_copywriter_text' => Yii::t('app', 'To Copywriter Text'),
            'to_copywriter_special' => Yii::t('app', 'To Copywriter Special'),
            'to_translator_languages' => Yii::t('app', 'To Translator Languages'),
            'to_developer_type' => Yii::t('app', 'To Developer Type'),
            'to_developer_status' => Yii::t('app', 'To Developer Status'),
            'shown_by_executor' => Yii::t('app', 'Shown By Executor'),
            'time' => Yii::t('app', 'Time'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->time = time();
                $this->status = 0;
                $this->bycreated = Yii::$app->user->id;

            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShownTasks()
    {
        return $this->hasMany(ShownTask::className(), ['task_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'bycreated']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutions()
    {
        return $this->hasMany(Execution::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLanguages()
    {
        return $this->hasMany(TaskLanguage::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::className(), ['id' => 'language_id'])->viaTable('task_language', ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskUsers()
    {
        return $this->hasMany(TaskUser::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('task_user', ['task_id' => 'id']);
    }

    public function getExecutorsByRole($role)
    {
        return ArrayHelper::map(User::find(['role' => $role])->all(), 'id', ['name']);
    }

    public function getToEx($item = null){
        if(is_numeric($item)){
            return $this->_toArr[$item];
        }
        return $this->_toArr;
    }

    public function getTypeSite($item = null){
        if(is_numeric($item)){
            return $this->_type_site[$item];
        }
        return $this->_type_site;
    }

    public function getStatusSite($item = null){
        if(is_numeric($item)){
            return $this->_status_site[$item];
        }
        return $this->_status_site;
    }

    public function getPriorities($item = null){
        if(is_numeric($item)){
            return $this->_priorities[$item];
        }
        return $this->_priorities;
    }

    public function getCopywriterType($item = null){
        if(is_numeric($item)){
            return $this->_copywriter_type[$item];
        }
        return $this->_copywriter_type;
    }

    public function getCopywriterTheme($item = null){
        if(is_numeric($item)){
            return $this->_copywriter_theme[$item];
        }
        return $this->_copywriter_theme;
    }

    public function getStatus($item = null){
        if(is_numeric($item)){
            return $this->_statuses[$item]['name'];
        }
        $statusArr = [];
        foreach ($this->_statuses as $key => $status){
            $statusArr[$key] = $status;
        }
        return $statusArr;
    }

    public function getStatusColor($item = null){
        if(is_numeric($item)){
            return $this->_statuses[$item]['color'];
        }
        return false;
    }

    public function getStatuses(){
        return $this->_statuses;
    }

    public function getShownByUser($task_id)
    {
        $user_id = Yii::$app->user->id;
        if(count(TaskUser::findAll(['user_id' => $user_id, 'task_id' => $task_id]))){
            return true;
        }
        return false;
    }
}
