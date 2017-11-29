<?php
/* @var $this yii\web\View
 * @var $hello string */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\components\CommentsWidget;
use app\components\CheckWidget;

$labelStatusArr = [0=>'success', 1=>'primary', 5 => 'danger', 15 => 'info'];
$viewLink = '';

echo "<h1>".$this->title."</h1>";

$link = Html::img('@web/images/+.png', ['height' => '30px']).' Создать новую задачу'/*, Url::to('task/create'), ['class' => 'btn-link'])*/;
$ul = '<ul class="modal-add-task">
        <li><img src="/images/clipboard.png"><a href="/task/create-task-copy">Копирайтер</a></li>
        <li><img src="/images/translate.png"><a href="/task/create-task-translator">Переводчик</a></li>
        <li><img src="/images/browser.png"><a href="/task/create-task-developer">Разработчик</a></li>
        <li><img src="/images/list.png"><a href="#">Шаблон для адалта</a></li>
        <li><img src="/images/clock.png"><a href="#">Последняя задача</a></li>
        <li><img src="/images/+.png"><a href="#">Создать новый шаблон</a></li>
    </ul>';

if(Yii::$app->user->identity->role == 1){
    Modal::begin([
        'header' => '<b>Создать новую задачу</b>',
        'toggleButton' => ['label' => $link, 'class' => 'button-add-task'],
    ]);
    echo $ul;
    Modal::end();
}

?>

<br><br>
<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <?=CommentsWidget::widget(['show_count' => false])?>
        </div>
        <?if(Yii::$app->user->identity->role == 1):?>
            <div class="col-lg-3">
                <?=CheckWidget::widget(['show_count' => false])?>
                <!--<a href="/settings/checkCount" tabindex="-1">На проверке<span>(<?/*=CheckWidget::widget()*/?>)</span></a>-->
            </div>
        <?endif;?>
    </div>
</div>
<br><br>


<?if(count($tasks)>0):?>
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
            <a <?=(is_null($tUser->shown_time))?"style='font-weight:bold'":""?> href="<?=Url::toRoute(['/task/'.$viewLink, 'id' => $task->id])?>">
                <?=$task->title?>
            </a>
        </div>
        <div class="col-sm-2">
        <span>
            <?=$task->getToEx($task->to);?><br>
            <span class="label label-<?=$labelStatusArr[$task->status]?>"><?=$task->getStatus($task->status);?></span>
        </span>
        </div>
        <div class="col-sm-2">
            <span><?=date('H:i', $task->time)?></span>
            <span><?=date('d.m.Y', $task->time)?></span>
        </div>
    </div>
    <hr>
<?endforeach;?>
<?endif;?>

