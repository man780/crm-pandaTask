<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16.10.2017
 * Time: 21:34
 */
use app\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\components\AlertWidget;
use yii\helpers\Url;

/* @var $content string
 * @var $this \yii\web\View */
AppAsset::register($this);
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
        <?php
        NavBar::begin(
            [
                'options' => [
                    'class' => 'navbar navbar-light /*navbar-fixed-top*/',
                    'id' => 'main-menu'
                ],
                'renderInnerContainer' => true,
                'innerContainerOptions' => [
                    'class' => 'container'
                ],
                'brandLabel' => '<img height="100%" src="'.\Yii::$app->request->BaseUrl.'/images/panda-logo.png"/>',
                'brandUrl' => [
                    '/site/index'
                ],
                'brandOptions' => [
                    'class' => 'navbar-brand'
                ]
            ]
        );
        if (!Yii::$app->user->isGuest):
            ?>

            <div class="navbar-form navbar-right">
                <!--<button class="btn btn-sm btn-default"
                        data-container="body"
                        data-toggle="popover"
                        data-trigger="focus"
                        data-placement="bottom"
                        data-title="<?/*= Yii::$app->user->identity['username'] */?>"
                        data-content="
                            <a href='<?/*= Url::to(['/main/profile']) */?>' data-method='post'>Мой профиль</a><br>
                            <a href='<?/*= Url::to(['/main/logout']) */?>' data-method='post'>Выход</a>
                        ">
                    <span class="glyphicon glyphicon-user"></span>
                </button>-->
                <a href="<?=Url::to(['/site/logout']);?>">Выход (<?=Yii::$app->user->identity->name?>)<!--<img height="50px" src="<?/*=Yii::$app->user->id*/?>">--></a>
            </div>
            <?php
        endif;
        $menuItems = [
            [
                'label' => 'Спрвочники <span class="glyphicon glyphicon-inbox"></span>',
                'items' => [
                    //'<li class="dropdown-header">Расширения</li>',
                    //'<li class="divider"></li>',
                    [
                        'label' => 'Отделы',
                        'url' => ['/branch/index']
                    ],
                    [
                        'label' => 'Языки',
                        'url' => ['/languages/index']
                    ]
                ]
            ],
            [
                'label' => 'О проекте <span class="glyphicon glyphicon-question-sign"></span>',
                'url' => [
                    '#'
                ],
                'linkOptions' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'style' => 'cursor: pointer; outline: none;'
                ],
            ],
        ];
        if (Yii::$app->user->isGuest):
            $menuItems[] = [
                'label' => 'Регистрация',
                'url' => ['/main/reg']
            ];
            $menuItems[] = [
                'label' => 'Войти',
                'url' => ['/main/login']
            ];
        endif;
        echo Nav::widget([
            'items' => $menuItems,
            'activateParents' => true,
            'encodeLabels' => false,
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ]
        ]);
        Modal::begin([
            'header' => '<h2>panda</h2>',
            'id' => 'modal'
        ]);
        echo '<div><ul>
                <li><a href="'.Url::to(['/task/create', 'to' => 1] ).'">Копирайтер</a></li>
                <li><a href="'.Url::to(['/task/create', 'to' => 2] ).'">Переводчик</a></li>
                <li><a href="'.Url::to(['/task/create', 'to' => 3] ).'">Разработчик</a></li>
                <li>Шаблон для Адалта</li>
                <li>Последняя задача</li>
                </ul></div>';
        Modal::end();
        ActiveForm::begin(
            [
                'action' => ['/find'],
                'method' => 'get',
                'options' => [
                    'class' => 'navbar-form navbar-center'
                ]
            ]
        );
        ?>
        <style>
            .input-group .form-control {
                position: relative;
                z-index: 2;
                -webkit-box-flex: 1;
                -webkit-flex: 1 1 auto;
                -ms-flex: 1 1 auto;
                flex: 1 1 auto;
                width: 1%;
                margin-bottom: 0;
            }
            .dropdown-item {
                display: block;
                width: 100%;
                padding: 3px 1.5rem;
                clear: both;
                font-weight: 400;
                color: #292b2c;
                text-align: inherit;
                white-space: nowrap;
                background: 0 0;
                border: 0;
            }
            .dropdown-divider {
                height: 1px;
                margin: .5rem 0;
                overflow: hidden;
                background-color: #eceeef;
            }

        </style>
        <!--<div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control col-xs-4" aria-label="Text input with dropdown button">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Поиск
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Все</a>
                            <a class="dropdown-item" href="#">по ID таска</a>
                            <a class="dropdown-item" href="#">по названию таска</a>
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">по тематике таска</a>
                            <a class="dropdown-item" href="#">по создателю таска</a>
                            <a class="dropdown-item" href="#">по дате создания таска</a>
                            <a class="dropdown-item" href="#">по дедлайну</a>
                            <a class="dropdown-item" href="#">по языкам таска</a>
                            <a class="dropdown-item" href="#">по типу таска</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <?
        ActiveForm::end();
        NavBar::end();
        ?>
        <div class="container">
            <?= AlertWidget::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <span class="badge">
                <span class="glyphicon glyphicon-copyright-mark"></span> Panda Media <?= date('Y') ?>
            </span>
        </div>
    </footer>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php
$this->endPage();