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
use app\components\CommentsWidget;
use app\components\CheckWidget;
use yii\helpers\Url;

/* @var $content string
 * @var $this \yii\web\View */
SiteAsset::register($this);
$this->beginPage();

$script = <<<JS
    /* Anything that gets to the document
    will hide the dropdown */
    $(document).click(function(){
      $(".dropdown-menu").hide();
    });
    
    /* Clicks within the dropdown won't make
       it past the dropdown itself */
    $(".dropdown a").click(function(e){
        $(".dropdown-menu").hide();
        $(this).next('.dropdown-menu').toggle();
        e.stopPropagation();
    });
JS;
$this->registerJs($script);
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
        <div class="top-navigation container">
            <div class="nav-logo">
                <a href="<?=Url::to(Yii::$app->getHomeUrl())?>"><?=Html::img('@web/images/panda-logo.png', ['alt'=>Yii::$app->name, 'height' => '40px'])?></a>
            </div>
            <ul class="navigation-list">
                <?//if(Yii::$app->user->identity->role == 1):?>
                <li class="navigation-item">
                    <a href="<?=Url::to('/calendar/index')?>">Календарь</a>
                </li>
                <?//endif;?>
                <li class="navigation-item dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Справочники
                        <span class="caret"></span>
                    </a>
                    <ul id="w3" class="dropdown-menu">
                        <li>
                            <a href="<?=Url::to('/language/index')?>" tabindex="-1">Языки</a>
                        </li>
                        <li>
                            <a href="<?=Url::to('/branch/index')?>" tabindex="-1">Отделы</a>
                        </li>
                    </ul>
                </li>
                <li class="navigation-item dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Все
                        <span class="caret"></span>
                    </a>
                    <ul id="w3" class="dropdown-menu">
                        <li>
                            <a href="<?=Url::to('/index')?>" tabindex="-1">Все</a>
                        </li>
                        <li>
                            <a href="<?=Url::to('/task/done')?>" tabindex="-1">Выполненные</a>
                        </li>
                        <li>
                            <a href="<?=Url::to('/task/in-order')?>" tabindex="-1">Задача поставлена</a>
                        </li>
                        <li>
                            <a href="<?=Url::to('/task/active')?>" data-method="post" tabindex="-1">В процессе выполнения</a>
                        </li>
                        <li>
                            <a href="<?=Url::to('/task/checking')?>" data-method="post" tabindex="-1">На проверке</a>
                        </li>
                    </ul>
                </li>
                <li class="navigation-item nickname dropdown">

                    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?=Yii::$app->user->identity->name. ' '. Html::img(Yii::$app->user->identity->avatar,
                            ['alt'=>Yii::$app->name, 'height' => '40px'])?>
                        <span class="caret"></span>
                    </a>
                    <ul id="w3" class="dropdown-menu">
                        <?if(Yii::$app->user->identity->role == 1):?>
                        <li><a href="/settings/users" tabindex="-1">Управления пользователями</a></li>
                        <?endif;?>
                        <li>
                            <a href="/settings" tabindex="-1">Настройки</a>
                        </li>
                        <li>
                            <a href="/logout" data-method="post" tabindex="-1">Выход</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>



        


        <?/*
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
        */?>


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