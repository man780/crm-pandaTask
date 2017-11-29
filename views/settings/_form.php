<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Branch;
use yii\helpers\ArrayHelper;

$branches = ArrayHelper::map(Branch::find()->all(), 'id', 'title');
$statuses = [0=>'Удаленный', 1=>"Не активно", 10=>"Активно",];
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'branch_id')->dropDownList($branches,
        ['class'=>'form-control', 'prompt' => 'Выберите отдел...']); ?>

    <?= $form->field($model, 'status')->dropDownList($statuses,
        ['class'=>'form-control', 'prompt' => 'Выберите статус...']) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
