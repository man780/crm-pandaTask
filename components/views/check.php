<?
use yii\bootstrap\Modal;
use yii\helpers\Url;

$link = 'На проверке: '.count($tasks);
Modal::begin([
    'header' => '<b>Список</b>',
    'toggleButton' => ['label' => $link, ],
]);?>

    <ul class="modal-add-task">
        <?foreach ($tasks as $task):?>
            <?
            if($task->to == 1){
                $viewLink = 'view-copy';
            }elseif ($task->to == 2){
                $viewLink = 'view-translator';
            }else{
                $viewLink = 'view-developer';
            }
            ?>
            <li><a href="<?=Url::toRoute(['/task/'.$viewLink, 'id' => $task->id]);?>"><?=$task->title?></a></li>
        <?endforeach;?>
    </ul>

<?Modal::end();?>