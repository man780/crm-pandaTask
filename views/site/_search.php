<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\TaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-sm-3">
        <?= $form->field($model, 'id') ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'title') ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'body') ?>
    </div>

    <div class="col-sm-3">
        <?= $form->field($model, 'priority') ?>
    </div>

    <div class="col-sm-3">
        <?= $form->field($model, 'deadline')->widget(DatePicker::classname(), [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]) ?>
    </div>

    <div class="col-sm-3">
        <?
        //echo $form->field($model, 'to')->dropDownList($model->to, ['class'=>'form-control', 'prompt' => 'Выберите тип...']);
        ?>
        <?php echo $form->field($model, 'to') ?>
    </div>

    <div class="col-sm-3">
        <?= $form->field($model, 'to_copywriter_type') ?>
    </div>


    <div class="col-sm-3">
        <?php echo $form->field($model, 'shown_by_executor') ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?/*= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) */?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
