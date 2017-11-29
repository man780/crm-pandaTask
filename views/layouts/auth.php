<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17.10.2017
 * Time: 16:37
 */

use app\assets\LoginAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\components\AlertWidget;
use yii\helpers\Url;

/* @var $content string
 * @var $this \yii\web\View */
LoginAsset::register($this);
$this->beginPage();
$script = <<< JS
        
    $('input').each(function(){
        $(this).focus();
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

    <hgroup>
        <h1>Panda Task</h1>
    </hgroup>

    <div class="wrap">

       <div class="container">
            <?= AlertWidget::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <span class="badge">
                <span class="glyphicon glyphicon-copyright-mark"></span> <span>Panda  <?= date('Y') ?></span>
            </span>
        </div>
    </footer>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php
$this->endPage();