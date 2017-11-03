<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Execution */

$this->title = Yii::t('app', 'Выполнение задания');
/*$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Executions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="execution-create">

    <div class="row">
        <div class="col-sm-4">
            Заказчик: <img src="<?=$model->task->taskCreator->avatar?>" height="50px"/> <b><?=$model->task->taskCreator->name?></b>
        </div>
        <div class="col-sm-4">
            Контакты: <b><?=$model->task->taskCreator->phone?></b>
        </div>
    </div>
    <h1><?= Html::encode($this->title) ?> "<?=$model->task->title?>"</h1>

    <div class="execution-form">



        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6])->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'advanced'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send to Check'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-danger'])?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
