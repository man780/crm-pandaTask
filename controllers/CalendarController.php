<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 02.11.2017
 * Time: 21:23
 */

namespace app\controllers;


use app\models\Task;
use yii\web\Controller;
use \yii2fullcalendar\models\Event;

class CalendarController extends Controller
{
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
        $tasks = Task::find()->all();
        foreach ($tasks as $task){
            $Event = new Event();
            $Event->id = 1;
            $Event->title = $task->title;
            $Event->start = date('Y-m-d\TH:i:s\Z', $task->deadline);
            if($task->deadline < time()){
                $Event->color = 'red';
            }
            $events[] = $Event;
        }

        return $this->render('index', [
            'events' => $events,
        ]);
    }
}