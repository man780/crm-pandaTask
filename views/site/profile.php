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
    <?//= $form->field($model, 'email') ?>
    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'language' => 'ru',
        'dateFormat' => 'dd.MM.yyyy',
    ]) ?>


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
