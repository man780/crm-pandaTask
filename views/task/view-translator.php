<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19.10.2017
 * Time: 13:49
 */
use yii\helpers\Html;
use yii\helpers\Url;
use \yii\widgets\ActiveForm;
use app\models\Comment;
use kartik\widgets\Select2;

$comment = new Comment();
$this->title = 'Задания ПЕРЕВОДЧИКУ';
$languageArr = [];
foreach ($model->taskLanguages as $language){
    $languageArr[] = '<b>'.$language->language->language.'</b>';
}
?>

<h1>
    <?=$model->getStatus($model->status);?>
    |
    <?=$this->title?>
</h1>

<div class="task-form">

    <h2>
        <?=$model->title?>
    </h2>
    <div class="row">
        <div class="col-md-3">
            <span>Дедлайн:</span>
            <b><?=date('d.m.Y', $model->deadline)?></b>
        </div>
        <div class="col-md-3">
            <span>Приоритет:</span>
            <b><?=$model->getPriorities($model->priority)?></b>
        </div>



        <div class="col-md-3">
            <span>Языки :</span>
            <?=join(',', $languageArr)?>
        </div>
        <div class="col-md-6">
            <span>Время добовления таска:</span>
            <b><?=date('d.m.Y H:i:s', $model->time)?></b>
        </div>
    </div>

    <div>
        <b>Текст таска :</b>
        <?=$model->body?>
    </div>

    <?if($model->status != 15):?>
        <hr>
        <div class="row">
            <a class="btn btn-success" href="<?=Url::to(['/execution/execute', 'task_id' => $model->id])?>">Выполнить задания</a>
            <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning'])?>

        </div>
    <?endif;?>
    <?if(Yii::$app->user->identity->role == 1):?>
        <hr>
        <div class="row">
            <?
            echo Html::a(
                'Удалить таск',
                Url::toRoute(['task/delete', 'id' => $model->id]),
                [
                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), // <-- confirmation works...
                    'data-method' => 'post',
                    'class' => 'btn btn-danger'
                ]
            );
            ?>
        </div>
    <?endif;?>

    <?$dir = Yii::$app->basePath.'/web/files/task/'.$model->id;
    if(is_dir($dir)):?>
    <hr>
    <div class="row">
        <h2>Прикрепленные файлы</h2>
        <?
            $dh  = opendir($dir);
            while (false !== ($filename = readdir($dh))) {
                if($filename == '.' || $filename == '..')continue;
                echo "Файл: <a href='/files/task/".$model->id.DIRECTORY_SEPARATOR.$filename."' target='_blank'>".$filename."</a><br>";
            }
        ?>
    </div>
    <?endif;?>

    <div>
        <h3>Ответственные испонители</h3>
        <?foreach($model->taskUsers as $tUser):?>
            <p>
                <span class="label label-primary"><?=$tUser->user->name?> </span>
            </p>
        <?endforeach;?>
    </div>

    <hr>
    <div class="row">
        <h3>Ответ исполнителя</h3>
        <?foreach($model->executions as $execution):?>
            <div class="col-md-3">
                <?=date('d.m.Y', $execution->time)?>
                <span class="label label-primary"><?=$execution->user->name?></span>
            </div>
            <div class="col-md-6"><?=$execution->text?></div>
            <?if(Yii::$app->user->identity->role == 1):?>
                <div class="col-md-3">
                    <?if(!$execution->status):?>
                        <a class="btn btn-primary" href="<?=Url::toRoute(['/execution/accept', 'execution_id' => $execution->id]);?>">
                            &#10004; Принять
                        </a>
                        
                        <a class="btn btn-danger" href="<?=Url::toRoute(['/execution/reject', 'execution_id' => $execution->id]);?>"> &#10005; Отклонить</a>
                    <?else:?>
                        &#9989; Принят
                    <?endif;?>

                </div>
            <?endif;?>
        <?endforeach;?>
    </div>

    <hr>
    <div class="commnentList">
        <h2>Вопрос ответы</h2>
        <?foreach ($model->comments as $comment):?>
            <div class="row">
                <div class="col-sm-1"><img src="<?=$comment->user->avatar?>" height="60px" class="image img-circle"/> </div>
                <div class="col-sm-3">
                    <b><?=$comment->user->name?></b>
                </div>
                <div class="col-sm-3">
                    <small><?=date('H:i d.m.Y', $comment->dcreated)?></small>
                </div>
                <div class="col-sm-9">
                    <?=$comment->body?>
                </div>
            </div>
        <?endforeach;?>
    </div>

    <div class="row">
        <h2>Добавить коментарий</h2>
        <div class="comment-form">

            <?php $form = ActiveForm::begin(['action' => Url::toRoute(['/comment/add', 'task_id' => $model->id]), 'method' => 'post']); ?>

            <?= $form->field($comment, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($comment, 'body')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
