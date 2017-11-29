<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 02.11.2017
 * Time: 21:23
 */

namespace app\controllers;


use app\models\Task;
use yii\helpers\Url;
use yii\web\Controller;
use \yii2fullcalendar\models\Event;

class CalendarController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'logout'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actions() {
        return [

        ];
    }

    /**
     * Lists all Execution models.
     * @return mixed
     */
    public function actionIndex()
    {
        $events = [];
        if(\Yii::$app->user->identity->role == 1){
            $tasks = Task::find()->all();
        }else{
            $user_id = \Yii::$app->user->id;
            $tasks = Task::find()->leftJoin('task_user tu', 'tu.task_id = task.id')->where(['tu.user_id' => $user_id])->all();
        }

        foreach ($tasks as $task){
            if($task->to == 1){
                $viewLink = 'view-copy';
            }elseif ($task->to == 2){
                $viewLink = 'view-translator';
            }else{
                $viewLink = 'view-developer';
            }
            $Event = new Event();
            $Event->id = $task->id;
            //$Event->title = '<a href="'.Url::toRoute(['/task/'.$viewLink, 'id' => $task->id]).'">'.$task->title.'</a>';
            $Event->title = $task->title;
            $Event->url = Url::toRoute(['/task/'.$viewLink, 'id' => $task->id]);
            $Event->start = date('Y-m-d\TH:i:s\Z', $task->deadline);
            $Event->backgroundColor = $task->getStatusColor($task->status);
            if($task->deadline < time()){
                $Event->color = 'red';
            }
            $events[] = $Event;
        }

        $task = new Task();
        $statuses = $task->getStatuses();
        return $this->render('index', [
            'events' => $events,
            'statuses' => $statuses,
        ]);
    }
}