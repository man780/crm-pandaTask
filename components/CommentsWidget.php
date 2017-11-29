<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14.11.2017
 * Time: 13:09
 */

namespace app\components;

use app\models\Comment;
use yii\base\Widget;
use yii\helpers\Html;

class CommentsWidget  extends Widget
{
    public $show_count;
    private $comments;

    public function init()
    {
        parent::init();
        $user_id = \Yii::$app->user->id;
        if(\Yii::$app->user->identity->role==1){
            $this->comments = Comment::find()->where(['status' => 1])->andWhere(['<>', 'user_id', $user_id])->all();
        }else{
            $user = \Yii::$app->user->identity;
            foreach ($user->tasks as $task){
                $idArr[] = $task->id;
            }
            $this->comments =[];
            if (!is_null($idArr)){
                $this->comments = Comment::find()->where(['status' => 1])->andWhere(['IN', 'task_id', $idArr])->all();
            }

            //vd($this->comments);
        }
    }

    public function run()
    {
        if($this->show_count){
            return count($this->comments);
        }else{
            return $this->render('comments', [
                'comments' => $this->comments,
            ]);
        }

    }
}