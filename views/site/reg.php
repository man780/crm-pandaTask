<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use app\models\Branch;

/* @var $this yii\web\View */
/* @var $model app\models\RegForm */
/* @var $form ActiveForm */
?>
<div class="main-reg">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="group">
        <?= $form->field($model, 'role', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        <div class="help-block">{hint}</div>'])->dropDownList([1=>'Создатель', 2=>'Испонитель'], ['prompt' => 'Выберите роль...']) ?>
    </div>
    <div class="group">
        <?= $form->field($model, 'email', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>']) ?>
    </div>
    <div class="group">
        <?= $form->field($model, 'password', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->passwordInput() ?>
    </div>
	<div class="group">
        <?= $form->field($model, 'name', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->textInput() ?>
    </div>
	<div class="group">
        <?= $form->field($model, 'phone', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->textInput() ?>
    </div>
	<div class="group">
	<?$params = [
        'prompt' => 'Выберите отдел...'
    ];?>
        <?= $form->field($model, 'branch_id', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        <div class="help-block">{hint}</div>'])->dropDownList(ArrayHelper::map(Branch::find()->all(), 'id', 'title'), $params) ?>
    </div>
	<div class="group">
        <?= $form->field($model, 'birthday', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->widget(DatePicker::classname(), [
			'language' => 'ru',
			'dateFormat' => 'dd.MM.yyyy',
		]) ?>
    </div>
	
	<div class="group">    
        <?= $form->field($model, 'avatar', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        <div class="help-block">{hint}</div>'])->fileInput()/*->widget(FileInput::classname(), [
			'attribute' => 'avatar',
		])*/ ?>
    </div>
	
	<div class="group">
		<?= Html::a('Войти', ['/login'], ['class' => 'btn-link']) ?> |
		<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn-submit']) ?>
	</div>
    <?php ActiveForm::end(); ?>

    <?php
    if($model->scenario === 'emailActivation'):
    ?>
    <i>*На указанный емайл будет отправлено письмо для активации аккаунта.</i>
    <?php
    endif;
    ?>

</div><!-- main-reg -->
