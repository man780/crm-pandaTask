<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>

    <?= $form->field($model, 'to_copywriter_type')->textInput() ?>

    <?= $form->field($model, 'to_copywriter_scope')->textInput() ?>

    <?= $form->field($model, 'to_copywriter_theme')->textInput() ?>

    <?= $form->field($model, 'to_copywriter_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'to_copywriter_special')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_translator_languages')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_developer_type')->textInput() ?>

    <?= $form->field($model, 'to_developer_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shown_by_executor')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
