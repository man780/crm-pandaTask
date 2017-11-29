<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoginForm */
/* @var $form ActiveForm */
?>
<div class="main-login">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->scenario === 'loginWithEmail'): ?>
        <?//= $form->field($model, 'email')->label()?>
        <div class="group">
            <!--<input type="text"><span class="highlight"></span><span class="bar"></span>-->
            <!--<label>E-mail</label>-->
            <?= $form->field($model, 'email', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->textInput(['autocomplete' => 'off'])?>
        </div>
    <?php else: ?>
        <?= $form->field($model, 'username', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>']); ?>
    <?php endif; ?>
    <div class="group">
        <!--<input type="text"><span class="highlight"></span><span class="bar"></span>
        <label>Пароль</label>-->
		
        <?= $form->field($model, 'password', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->passwordInput() ?>
    </div>

	<?//= $form->errorSummary($model, ['header' => '', 'class' => 'has-error'])?>
    
	<div class="group">
		<?= $form->field($model, 'rememberMe', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>'])->checkbox() ?>
	</div>
	<br>
	<br>
	<br>
		<?= Html::a('Забыли пароль?', ['/site/send-email'], ['class' => 'btn-link']) ?>
    <br>
    <br>

    <div class="group">
        <?= Html::a('Зарегестрироваться', ['/reg'], ['class' => 'btn-link']) ?> |
        <?= Html::submitButton(' Войти', ['class' => 'btn-submit']) ?>
    </div>
    
	<?php ActiveForm::end(); ?>

	
</div><!-- main-login -->
