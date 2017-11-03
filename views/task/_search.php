<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'body') ?>

    <?= $form->field($model, 'priority') ?>

    <?= $form->field($model, 'deadline') ?>

    <?php // echo $form->field($model, 'to') ?>

    <?php // echo $form->field($model, 'to_copywriter_type') ?>

    <?php // echo $form->field($model, 'to_copywriter_scope') ?>

    <?php // echo $form->field($model, 'to_copywriter_theme') ?>

    <?php // echo $form->field($model, 'to_copywriter_text') ?>

    <?php // echo $form->field($model, 'to_copywriter_special') ?>

    <?php // echo $form->field($model, 'to_translator_languages') ?>

    <?php // echo $form->field($model, 'to_developer_type') ?>

    <?php // echo $form->field($model, 'to_developer_status') ?>

    <?php // echo $form->field($model, 'shown_by_executor') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
