<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14.11.2017
 * Time: 13:08
 */

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\models\Task;

class CheckWidget  extends Widget
{
    public $show_count;
    public $count;
    private $tasks;

    public function init()
    {
        parent::init();
        $this->tasks = Task::findAll(['status' => 5]);
    }

    public function run()
    {
        if($this->show_count){
            return count($this->tasks);
        }else{
            return $this->render('check', [
                'tasks' => $this->tasks,
            ]);
        }

    }
}