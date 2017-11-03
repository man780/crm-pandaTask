<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16.10.2017
 * Time: 21:34
 */
use app\assets\SiteAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
//use yii\widgets\Breadcrumbs;
use app\components\AlertWidget;
use yii\helpers\Url;

/* @var $content string
 * @var $this \yii\web\View */
SiteAsset::register($this);
$this->beginPage();
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
        <?= Html::csrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <?php $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']); ?>
        <title><?= Yii::$app->name ?></title>
        <?php $this->head() ?>




    </head>
    <body>
    <?php $this->beginBody(); ?>
    <div class="wrap">
        <?
        NavBar::begin([ // отрываем виджет
            'brandLabel' => Html::img('@web/images/panda-logo.png', ['alt'=>Yii::$app->name, 'height' => '40px']),//'Моя организация', // название организации

            'brandUrl' => Yii::$app->homeUrl, // ссылка на главную страницу сайта
            'options' => [
                'class' => 'navbar-light navbar-fixed-top', // стили главной панели
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'], // стили ul
            'items' => [
                [
                    'label' => '<form class="navbar-form navbar-left">
                                    <div class="form-group">
                                        <input type="text" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-default">Поиск</button>
                                 </form>',
                    'url' => null,
                    'encode' => false,
                ],
                [
                    'label' => 'Все',
                    'url' => null,
                    //'linkOptions' => ['data-method' => 'post'],
                    'items' => [
                        ['label' => 'Выполненные', 'url' => '/task/done'],
                        ['label' => 'В очереди', 'url' => '/task/order'],
                        ['label' => 'Активные', 'url' => '/task/active'],
                    ],
                ],
                Yii::$app->user->isGuest ? // Если пользователь гость, показыаем ссылку "Вход", если он авторизовался "Выход"
                    ['label' => 'Вход', 'url' => ['/site/login']] :
                    [
                        'label' => Yii::$app->user->identity->name. ' '. Html::img(Yii::$app->user->identity->avatar, ['alt'=>Yii::$app->name, 'height' => '40px']),
                        'url' => null, 'encode' => false,
                        //'linkOptions' => ['data-method' => 'post'],
                        'items' => [
                            ['label' => 'Сменить вид', 'url' => '#'],
                            ['label' => 'Настройки', 'url' => '#'],
                            '<li class="divider"></li>',
                            ['label' => 'Выход', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post'],],
                        ],
                    ],



            ],
        ]);
        NavBar::end(); // закрываем виджет
        ?>

        <br/>
        <br/>
        <br/>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?//= Alert::widget() ?>
            <?= $content ?>
        </div>

    </div>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php
$this->endPage();