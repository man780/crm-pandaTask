<?php
/* @var $this yii\web\View
 * @var $hello string */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$viewLink = '';
$this->title = 'Главная страница';
?>

    <? $link = Html::img('@web/images/+.png', ['height' => '30px']).' Создать новую задачу'/*, Url::to('task/create'), ['class' => 'btn-link'])*/;?>

    <? $ul = '<ul class="">
        <li><a href="/task/create-task-copy">Копирайтер</a></li>
        <li><a href="/task/create-task-translator">Переводчик</a></li>
        <li><a href="/task/create-task-developer">Разработчик</a></li>
        <li><a href="#">Шаблон для адалта</a></li>
        <li><a href="#">Последняя задача</a></li>
        <li><a href="#">Создать новый шаблон</a></li>
    </ul>';?>
    <?
        if(Yii::$app->user->identity->role == 1){
            Modal::begin([
                'header' => '<b>Создать новую задачу</b>',
                'toggleButton' => ['label' => $link],
            ]);

            echo $ul;

            Modal::end();
        }
    ?>
<br><br>


        <?foreach ($tasks as $task):?>
        <div class="row">
            <?foreach ($task->taskUsers as $k => $tUser):?>
                <?
                if($task->to == 1){
                    $viewLink = 'view-copy';
                }elseif ($task->to == 2){
                    $viewLink = 'view-translator';
                }else{
                    $viewLink = 'view-developer';
                }
                ?>
                <?if($k==1)break;?>
                <div class="col-sm-1">
                    <img height="30px" src="<?=$tUser->user->avatar;?>" />
                </div>
            <?endforeach;?>
            <div class="col-sm-4">
                <a <?=(is_null($task->shown_by_executor))?"style='font-weight:bold'":""?> href="<?=Url::toRoute(['/task/'.$viewLink, 'id' => $task->id])?>"><?=$task->title?></a>
            </div>
            <div class="col-sm-2">
                <span>
                    <?=$task->getToEx($task->to);?><br>
                    <?=$task->getStatus($task->status);?>
                </span>
            </div>
            <div class="col-sm-2">
                <span><?=date('H:i', $task->time)?></span>
                <span><?=date('d.m.Y', $task->time)?></span>
            </div>
        </div>
        <hr>
        <?endforeach;?>


