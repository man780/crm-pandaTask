<?
use yii\bootstrap\Modal;
use yii\helpers\Url;

$link = 'Комментарии: '.count($comments);
Modal::begin([
    'header' => '<b>Список коментариев</b>',
    'toggleButton' => ['label' => $link, ],
]);?>

<ul class="modal-add-task">
<?foreach ($comments as $comment):?>
    <?
    if($comment->task->to == 1){
        $viewLink = 'view-copy';
    }elseif ($comment->task->to == 2){
        $viewLink = 'view-translator';
    }else{
        $viewLink = 'view-developer';
    }
    ?>
    <li><a href="<?=Url::toRoute(['/task/'.$viewLink, 'id' => $comment->task_id]);?>"><?=$comment->title?></a></li>
<?endforeach;?>
</ul>

<?Modal::end();?>