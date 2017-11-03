<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use app\models\Branch;


/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */
?>
<div class="main-profile">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    if($model->user)
        echo $form->field($model->user, 'username');
    ?>
    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'second_name') ?>
    <?= $form->field($model, 'middle_name') ?>

    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
    ]) ?>

    <?
    $params = [
        'prompt' => 'Выберите пол'
    ];
    echo $form->field($model, 'gender')->dropDownList([1=>'Муж', 0=>'Жен'], ['class'=>'form-control', $params]);
    ?>

    <?
    $params = [
        'prompt' => 'Выберите отдел'
    ];
    echo $form->field($model, 'branch_id')->dropDownList(ArrayHelper::map(Branch::find()->all(), 'id', 'title')) ?>

    <?= $form->field($model, 'skype') ?>
    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'telegramm') ?>

    <img src="<?= $model->avatar?>" height="50px"/>
    <?= $form->field($model, 'avatar')->fileInput(['class' => 'btn btn-default ']); ?>

	<div class="form-group">
		<?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Назад', '/index', ['class' => 'btn btn-danger']) ?>
	</div>
    <?php ActiveForm::end(); ?>

</div><!-- main-profile -->
