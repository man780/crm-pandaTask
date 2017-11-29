<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'username',
            'email:email',
            //'avatar',
            [
                'label' => Yii::t('app','Avatar'),
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->avatar),[
                        'alt'=>'yii2 - картинка в gridview',
                        'style' => 'width:25px;'
                    ]);
                },
            ],
            'name',
            'birthday:date',
            'branch_id',
            'skype',
            'phone',
            'telegramm',
            [
                'attribute'=>'status',
                'value' => function($data){
                    $arr = array(0=>'Удаленный', 1=>"Не активно", 10=>"Активно", );
                    return $arr[$data->status];
                },
                'filter'=>array(0=>'Удаленный', 1=>"Не активно", 10=>"Активно", ),
            ],
            'role',
            //'auth_key',
            //'secret_key',
            //'created_at:datetime',
            //'updated_at:datetime',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
