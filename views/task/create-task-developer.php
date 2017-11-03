<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;
use yii\jui\DatePicker;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Добавить задания РАЗРАБОТЧИКУ';
$script = <<< JS
        
    $('.add-executor').click(function(){
        var executor = $('.executor:last').clone();
        //console.log(executor.find('.executorAuthorities'));
        num = $('.executor').length;
        executor.find('.executorAuthorities').attr('id', 'w'+num);
        $('.executor-list').append(executor);
    });
    $('.remove-executor').click(function(){
        if($('.executor').length > 1)
        $('.executor:last').remove();
    });
JS;
$this->registerJs($script);
?>

<h2>
    <?=$this->title?>
</h2>
<div class="task-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6])->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advanced'
    ]) ?>

    <?
    echo $form->field($model, 'priority')->dropDownList($model->priorities, ['class'=>'form-control', 'prompt' => 'Выберите приоритет...']);
    ?>

    <?= $form->field($model, 'deadline')->widget(DatePicker::classname(), [
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
        'options' => ['class' => 'form-control']
    ]) ?>

    <?
        echo $form->field($model, 'to_developer_type')->dropDownList($model->typeSite, ['class'=>'form-control', 'prompt' => 'Выберите тип...']);
    ?>
    <?//= $form->field($model, 'to_developer_type')->textInput() ?>

    <?
        echo $form->field($model, 'to_developer_status')->dropDownList($model->statusSite, ['class'=>'form-control', 'prompt' => 'Выберите состояние...']);
    ?>

    <div class="form-group">
        <div class="container  executor-list">
            <?if(!$model->isNewRecord):?>
                <?foreach ($executors as $executor):?>
                    <div class="row executor">
                        <?=Html::label('Исполнитель', 'executor')?>
                        <?
                        $items = $model->getExecutorsByRole(1);
                        echo Html::dropDownList('executor[]', $executor['executor_authority_id'], $items, ['class'=>'form-control']);
                        ?>
                    </div>
                <?endforeach;?>
            <?else:?>
                <div class="row executor">
                    <?=Html::label('Исполнитель', 'executor')?>
                    <?
                    $items = $model->getExecutorsByRole(1);
                    echo Html::dropDownList('executor[]', '', $items, ['class'=>'form-control', 'prompt'=>'Выберите исполнителя ...']);
                    ?>
                </div>
            <?endif;?>
        </div>
        <div class="container row">
            <a href="javascript:void(0);" class="btn btn-success add-executor">+</a>
            <a href="javascript:void(0);" class="btn btn-danger remove-executor">-</a>
        </div>
    </div>

    <div class='form-group'>
        <label>Выберите файлы (Можно любые файлы и несколько файлов)</label>
        <?
        echo FileInput::widget([
            'name' => 'file[]',
            //'attribute' => 'attachment_1',
            'options' => ['multiple' => true, 'class'=>'form-control'],

        ]);
        ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Back'), '/index', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
