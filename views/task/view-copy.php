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

$comment = new Comment();
$this->title = 'Задания КОПИРАЙТЕРУ';
?>

<h1>
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
            <span>Тип :</span>
            <b><?=$model->getCopywriterType($model->to_copywriter_type)?></b>
        </div>
        <div class="col-md-3">
            <span>Объем :</span>
            <b><?=$model->to_copywriter_scope?></b>
        </div>
        <div class="col-md-3">
            <span>Тематика :</span>
            <b><?=$model->getCopywriterTheme($model->to_copywriter_theme)?></b>
        </div>


        <div class="col-md-3">
            <span>Особые указания :</span>
            <b><?=$model->to_copywriter_special?></b>
        </div>
        <div class="col-md-6">
            <span>Время добовления таска:</span>
            <b><?=date('d.m.Y H:i:s', $model->time)?></b>
        </div>


    </div>
    <div class="row">
        <div class="col-md-8">
                <span>Текст :</span>
                <b><?=$model->to_copywriter_text?></b>
        </div>
    </div>
    <div>
        <b>Текст таска :</b>
        <?=$model->body?>
    </div>

    <hr>
    <div class="row">
        <a class="btn btn-success" href="<?=Url::to(['/execution/execute', 'task_id' => $model->id])?>">Выполнить задания</a>
        <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-danger'])?>
    </div>

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
            <div class="col-lg-9"><?=$execution->text?></div>

        <?endforeach;?>
    </div>

    <hr>
    <div class="row">
        <h2>Вопрос ответы</h2>
        <div class="col-sm-1"><img src="/images/user/copy/8.jpg" height="60px" class="image img-circle"/> </div>
        <div class="col-sm-3">
            <b>Мурат</b>
        </div>
        <div class="col-sm-3">
            <small>16:07 02.11.2017</small>
        </div>
        <div class="col-sm-9">
            Первый Текст
        </div>


    </div>
    <div class="row">
        <h2>Добавить коментарий</h2>
        <div class="comment-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($comment, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($comment, 'body')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
