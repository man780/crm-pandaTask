<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 02.11.2017
 * Time: 21:25
 */
use yii\helpers\Url;
?>

<?= \yii2fullcalendar\yii2fullcalendar::widget(array(
    'events'=> $events,
    'options' => [
        'lang' => 'ru',
        //... more options to be defined here!
    ],
));