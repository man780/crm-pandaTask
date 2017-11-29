<?php
namespace app\controllers;

use app\models\Task;
use app\models\TaskSearch;
use Yii;
use app\models\RegForm;
use app\models\LoginForm;
use app\models\User;
use app\models\Profile;
use app\models\SendEmailForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AccountActivation;

class MainController extends BehaviorsController
{
    //public $layout = 'basic';
    public $defaultAction = 'index';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'logout', 'profile'],
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

        //$role = Yii::$app->user->identity->role;
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
        //var_dump($profile);
        $task = new Task();

        return $this->render(
            'index',
            [
                'profile' => $profile,
                'task' => $task,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionProfile()
    {
        $model = ($model = Profile::findOne(Yii::$app->user->id)) ? $model : new Profile();
        $old_avatar = $model->avatar;
        if($model->load(Yii::$app->request->post())):
            $post = Yii::$app->request->post();

            $model->birthday = ($post['Profile']['birthday'] == '') ? null : strtotime($post['Profile']['birthday']);
            $model->branch_id = ($post['Profile']['branch_id'] == '') ? null : $post['Profile']['branch_id'];
            $model->skype = ($post['Profile']['skype'] == '') ? null : $post['Profile']['skype'];
            $model->phone = ($post['Profile']['phone'] == '') ? null : $post['Profile']['phone'];
            $model->telegramm = ($post['Profile']['telegramm'] == '') ? null : $post['Profile']['telegramm'];
            if($model->validate()){
                $avatar = $_FILES['Profile'];
                if(is_file($avatar['tmp_name']['avatar']) && (
                        exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_GIF ||
                        exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_JPEG ||
                        exif_imagetype($avatar['tmp_name']['avatar']) == IMAGETYPE_PNG
                    )):
                    $image = '/images/profiles/'.Yii::$app->user->identity->username.'/'.$avatar['name']['avatar'];
                    if(!is_dir(Yii::$app->basePath.'\web\images\profiles'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username))
                    mkdir(Yii::$app->basePath.'\web\images\profiles'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username);
                    if (!move_uploaded_file($avatar['tmp_name']['avatar'],
                        Yii::$app->basePath.'\web\images\profiles'.DIRECTORY_SEPARATOR.Yii::$app->user->identity->username.DIRECTORY_SEPARATOR.$avatar['name']['avatar']))
                        Yii::$app->session->setFlash('success', 'Файл не сохранён');
                    $model->avatar = $image;
                else:
                    $model->avatar = $old_avatar;
                endif;
                //print_r($model->attributes); die;
                if($model->updateProfile()):
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

    public function actionReg()
    {
        $this->layout = 'auth';
        $emailActivation = Yii::$app->params['emailActivation'];
        $model = $emailActivation ? new RegForm(['scenario' => 'emailActivation']) : new RegForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()):
            //var_dump($model); die;
            if ($user = $model->reg()):
                if ($user->status === User::STATUS_ACTIVE):
                    if (Yii::$app->getUser()->login($user)):

                        return $this->redirect(Url::to(['/main/profile']));
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

        return $this->redirect(Url::to(['/main/login']));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/main/index']);
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
            '/main/login',
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
        try {
            $model = new ResetPasswordForm($key);
        }
        catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/main/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
