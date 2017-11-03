<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SendEmailForm */
/* @var $form ActiveForm */
?>
<div class="main-sendEmail">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email', ['template' => '{input}<span class="highlight"></span><span class="bar"></span>
        {label}<div class="help-block">{hint}</div>']) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- main-sendEmail -->
