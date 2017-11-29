<?php

namespace app\controllers;

use app\models\AccountActivation;
use app\models\ResetPasswordForm;
use app\models\SendEmailForm;
use app\models\Task;
use app\models\TaskSearch;
use Yii;
use app\models\RegForm;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

class SiteController extends Controller
{
    public $layout = 'main';
    public $defaultAction = 'index';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'logout'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }



    public function actionIndex()
    {

        $role = Yii::$app->user->identity->role;
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($role == 1){
            $tasks = Task::find()->orderBy('id DESC')->all();
        }else{
            $userModel = Yii::$app->user->identity;
            $tasks = $userModel->tasks;

            
        }

        return $this->render(
            'index',
            [
                'user' => Yii::$app->user->identity,
                'tasks' => $tasks,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionSettings()
    {
        $model = ($model = User::findOne(Yii::$app->user->id)) ? $model : new User();
        $old_avatar = $model->avatar;
        if($model->load(Yii::$app->request->post())):
            $post = Yii::$app->request->post();

            $model->birthday = ($post['User']['birthday'] == '') ? null : strtotime($post['User']['birthday']);
            $model->branch_id = ($post['User']['branch_id'] == '') ? null : $post['User']['branch_id'];
            $model->skype = ($post['User']['skype'] == '') ? null : $post['User']['skype'];
            $model->phone = ($post['User']['phone'] == '') ? null : $post['User']['phone'];
            $model->telegramm = ($post['User']['telegramm'] == '') ? null : $post['User']['telegramm'];
            if($model->validate()){
                $avatar = $_FILES['User'];
                if(is_file($avatar['tmp_name']['avatar']) && (
                        exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_GIF ||
                        exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_JPEG ||
                        exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_PNG
                    )):
                    $image = '/images/user/'.Yii::$app->user->identity->username.'/'.$avatar['name']['avatar'];

                    $this->removeDirectory(Yii::$app->basePath.'/web/images/user'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username);
                    if(!is_dir(Yii::$app->basePath.'/web\images/user'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username))
                        mkdir(Yii::$app->basePath.'/web/images/user'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username);
                    //vd($image);
                    if (!move_uploaded_file($avatar['tmp_name']['avatar'],
                        Yii::$app->basePath.'/web/images/user'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username.DIRECTORY_SEPARATOR.$avatar['name']['avatar']))
                        Yii::$app->session->setFlash('success', 'Файл не сохранён');
                    $model->avatar = $image;
                else:
                    $model->avatar = $old_avatar;
                endif;
                //print_r($model->attributes); die;
                if($model->updateUser()):
                    Yii::$app->session->setFlash('success', 'Профиль изменен');
                else:
                    Yii::$app->session->setFlash('error', 'Профиль не изменен');
                    Yii::error('Ошибка записи. Профиль не изменен');
                    return $this->refresh();
                endif;
            }


        endif;
        $model->birthday = date('d.m.Y', $model->birthday);

        return $this->render(
            'profile',
            [
                'model' => $model
            ]
        );
    }

    private function removeDirectory($dir) {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function actionReg()
    {
        $this->layout = 'auth';
        $emailActivation = Yii::$app->params['emailActivation'];
        $model = $emailActivation ? new RegForm(['scenario' => 'emailActivation']) : new RegForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            $post = Yii::$app->request->post();
            $model->phone = $post['RegForm']['phone'];
            //var_dump($model->attributes); die;
            $usernameArr = explode('@', $model->email);
            $model->username = $usernameArr[0];

            $avatar = $_FILES['RegForm'];
            //var_dump($model->attributes); die;
            if(is_file($avatar['tmp_name']['avatar']) && (
                    exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_GIF ||
                    exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_JPEG ||
                    exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_PNG
                )) {
                $image = '/images/user/' . $model->username . '/' . $avatar['name']['avatar'];
                if (!is_dir(Yii::$app->basePath . '/web/images/user' . DIRECTORY_SEPARATOR . $model->username))
                    mkdir(Yii::$app->basePath . '/web/images/user' . DIRECTORY_SEPARATOR . $model->username);
                if (!move_uploaded_file($avatar['tmp_name']['avatar'],
                    Yii::$app->basePath . '/web/images/user' . DIRECTORY_SEPARATOR . $model->username . DIRECTORY_SEPARATOR . $avatar['name']['avatar'])
                )
                    Yii::$app->session->setFlash('success', 'Файл не сохранён');
                $model->avatar = $image;
            }
            if ($user = $model->reg()):
                if ($user->status === User::STATUS_ACTIVE):
                    if (Yii::$app->user->login($user)):
                        return $this->redirect(Url::to(['/site/index']));
                    endif;
                else:
                    if($model->sendActivationEmail($user)):
                        Yii::$app->session->setFlash('success', 'Письмо с активацией отправлено на емайл <strong>'.Html::encode($user->email).'</strong> (проверьте папку спам).');
                    else:
                        Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        Yii::error('Ошибка отправки письма.');
                    endif;
                    return $this->refresh();
                endif;
            else:
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'reg',
            [
                'model' => $model
            ]
        );
    }

    public function actionActivateAccount($key)
    {
        try {
            $user = new AccountActivation($key);
        }
        catch(InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user->activateAccount()):
            Yii::$app->session->setFlash('success', 'Активация прошла успешно. <strong>'.Html::encode($user->username).'</strong> вы теперь с Panda!!!');
        else:
            Yii::$app->session->setFlash('error', 'Ошибка активации.');
            Yii::error('Ошибка при активации.');
        endif;

        return $this->redirect(Url::to(['/site/login']));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/site/index']);
    }

    public function actionLogin()
    {
        $this->layout = 'auth';
        if (!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;

        $loginWithEmail = Yii::$app->params['loginWithEmail'];

        $model = $loginWithEmail ? new LoginForm(['scenario' => 'loginWithEmail']) : new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()):
            return $this->goBack();
        endif;

        return $this->render(
            '/site/login',
            [
                'model' => $model
            ]
        );
    }

    public function actionSearch()
    {
        $search = Yii::$app->session->get('search');
        Yii::$app->session->remove('search');

        if ($search):
            Yii::$app->session->setFlash(
                'success',
                'Результат поиска'
            );
        else:
            Yii::$app->session->setFlash(
                'error',
                'Не заполнена форма поиска'
            );
        endif;

        return $this->render(
            'search',
            [
                'search' => $search
            ]
        );
    }

    public function actionSendEmail()
    {
        $this->layout = 'auth';
        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('warning', 'Проверьте емайл.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        $this->layout = 'auth';
        try {
            $model = new ResetPasswordForm($key);
        }
        catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/site/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}