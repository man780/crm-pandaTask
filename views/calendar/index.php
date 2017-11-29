<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 02.11.2017
 * Time: 21:25
 */
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$JSEventClick = <<<EOF
function(calEvent, jsEvent, view) {
    $('#modalTitle').html('Таск');
    $('#modalBody').html('Заголовок таска: '+calEvent.title);    
    $('#modalBody').append('<br><a href="'+calEvent.url+'">Подробно</a>');
    $('#eventUrl').attr('href',calEvent.url);
    $('#calendarModal').modal();
    return false;
}
EOF;


$JSEventDrop = <<<EOF
function(event, delta, revertFunc) {
    if (!confirm("Вы уверены, что хотите изменить дедлайн?")) {
        revertFunc();
    }else{
        $.ajax({
            url: '/task/edit-deadline',
            data: '&deadline='+ event.start.format() +'&id='+ event.id,
            type: "POST",
            success: function(data) {
                if(data){
                    alert('Дедлайн успешно изменен!');
                }else{
                    alert('Ошибка: Обратитесь администратору!');
                }
            }
        });
    }
}
EOF;
if(Yii::$app->user->identity->role != 1){
    $JSEventDrop = <<<EOF
    function(event, delta, revertFunc) {
        alert('Вы не можете менять дедлайн!');
        revertFunc();
    }
EOF;
}


Modal::begin();
Modal::end();

if(Yii::$app->user->identity->role == 1){
    $link = Html::img('@web/images/+.png', ['height' => '30px']).' Создать новую задачу';

    $ul = '<ul class="modal-add-task">
        <li><img src="/images/clipboard.png"><a href="/task/create-task-copy">Копирайтер</a></li>
        <li><img src="/images/translate.png"><a href="/task/create-task-translator">Переводчик</a></li>
        <li><img src="/images/browser.png"><a href="/task/create-task-developer">Разработчик</a></li>
        <li><img src="/images/list.png"><a href="#">Шаблон для адалта</a></li>
        <li><img src="/images/clock.png"><a href="#">Последняя задача</a></li>
        <li><img src="/images/+.png"><a href="#">Создать новый шаблон</a></li>
    </ul>';
    Modal::begin([
        'header' => '<b>Создать новую задачу</b>',
        'toggleButton' => ['label' => $link, 'class' => 'button-add-task'],
    ]);

    echo $ul;

    Modal::end();
}
?>

<Br>
<Br>
<style>
    .info{
        margin-bottom: 10px;
    }
    .info .infoBlocks{
        padding: 3px;
        border-radius: 3px;
        color: #fff;
    }
</style>
<div class="info">
    <h3>Значения цветов</h3>

    <div>
        <?foreach ($statuses as $key => $status):?>
        <span class="infoBlocks" style="background-color:<?=$status['color']?>"><?=$status['name']?></span>
        <?endforeach;?>
    </div>
</div>

<?= \yii2fullcalendar\yii2fullcalendar::widget(
    array(
        'events' => $events,
        'options' => [
            'lang' => 'ru',
        ],
        'id' => 'calendar',

        'clientOptions' =>[
            'allDaySlot' => false,
            'eventLongPressDelay' => 1500,
            'selectLongPressDelay' => true,
            'hiddenDays'=> [0],
            'selectable' => true,
            'selectHelper' => true,
            'editable' => true,
            'droppable' => true,
            'eventDrop' => new JsExpression($JSEventDrop),
            'event' => true,
            'eventClick' => new JsExpression($JSEventClick),
            ]
    )
);?>



<div id="calendarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"> </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>