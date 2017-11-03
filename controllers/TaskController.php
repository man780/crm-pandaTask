<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use app\models\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use app\models\TaskUser;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'view-developer', 'view-copy', 'view-translator', 'create-task-copy', 'create-task-translator', 'create-task-developer',
                    'done', 'active'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewCopy($id)
    {
        $model = $this->findModel($id);
        if(is_null($model->shown_by_executor)){
            $model->shown_by_executor = 1;
            $model->save();
        }
        return $this->render('view-copy', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewDeveloper($id)
    {
        $model = $this->findModel($id);
        if(is_null($model->shown_by_executor)){
            $model->shown_by_executor = 1;
            $model->status = 1;
            if(!$model->save()){
                vd($model->errors);
            }
        }
        return $this->render('view-developer', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewTranslator($id)
    {
        $model = $this->findModel($id);
        if(is_null($model->shown_by_executor)){
            $model->shown_by_executor = 1;
            $model->save();
        }
        return $this->render('view-translator', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateTaskCopy()
    {
        if(Yii::$app->user->identity->role != 1){
            throw new HttpException(403);
        }

        $model = new Task();

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->to = 1;
            $this->status = 0;
            $model->deadline = strtotime($post['Task']['deadline']);
            if($model->save()){
                foreach ($post['executor'] as $key => $pExec){
                    $eventExec = new TaskUser();

                    $eventExec->user_id = $pExec;
                    $eventExec->task_id = $model->id;
                    $eventExec->created_at = date('Y-m-d H:i:s');
                    //var_dump($eventExec->attributes);
                    $eventExec->save();
                }
                $files = $_FILES['file'];
                if(is_array($files))
                    foreach ($files['tmp_name'] as $key => $file):
                        if(is_file($file)){
                            $filePlace = Yii::$app->basePath.'\web\files\task'.DIRECTORY_SEPARATOR.$model->id;

                            if(!is_dir($filePlace))mkdir($filePlace);

                            if (!move_uploaded_file($file, Yii::$app->basePath.'\web\files\task\\'.$model->id.DIRECTORY_SEPARATOR.$files['name'][$key])){
                                Yii::$app->session->setFlash('success', 'Файл не сохранён');
                            }

                        }
                    endforeach;
                return $this->redirect(['view-copy', 'id' => $model->id]);
            }

        }

        return $this->render('create-task-copy', [
            'model' => $model,
        ]);
    }

    public function actionCreateTaskTranslator()
    {
        if(Yii::$app->user->identity->role != 1){
            throw new HttpException(403);
        }

        $model = new Task();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->to = 2;
            $this->status = 0;
            $model->deadline = strtotime($post['Task']['deadline']);
            if($model->save()){
                foreach ($post['executor'] as $key => $pExec){
                    $eventExec = new TaskUser();

                    $eventExec->user_id = $pExec;
                    $eventExec->task_id = $model->id;
                    $eventExec->created_at = date('Y-m-d H:i:s');
                    $eventExec->save();
                }
                $files = $_FILES['file'];
                if(is_array($files))
                    foreach ($files['tmp_name'] as $key => $file):
                        if(is_file($file)){
                            $filePlace = Yii::$app->basePath.'\web\files\task'.DIRECTORY_SEPARATOR.$model->id;

                            if(!is_dir($filePlace))mkdir($filePlace);

                            if (!move_uploaded_file($file, Yii::$app->basePath.'\web\files\task\\'.$model->id.DIRECTORY_SEPARATOR.$files['name'][$key])){
                                Yii::$app->session->setFlash('success', 'Файл не сохранён');
                            }

                        }
                    endforeach;
                return $this->redirect(['view-translator', 'id' => $model->id]);
            }

        }

        return $this->render('create-task-translator', [
            'model' => $model,
        ]);
    }

    public function actionCreateTaskDeveloper()
    {
        if(Yii::$app->user->identity->role != 1){
            throw new HttpException(403);
        }

        $model = new Task();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->to = 3;
            $this->status = 0;
            $model->deadline = strtotime($post['Task']['deadline']);

            //var_dump($files);die;
            if($model->save()){
                foreach ($post['executor'] as $key => $pExec){
                    $eventExec = new TaskUser();

                    $eventExec->user_id = $pExec;
                    $eventExec->task_id = $model->id;
                    $eventExec->created_at = date('Y-m-d H:i:s');
                    $eventExec->save();
                }
                $files = $_FILES['file'];
                if(is_array($files))
                    foreach ($files['tmp_name'] as $key => $file):
                        if(is_file($file)){
                            $filePlace = Yii::$app->basePath.'\web\files\task'.DIRECTORY_SEPARATOR.$model->id;

                            if(!is_dir($filePlace))mkdir($filePlace);

                            if (!move_uploaded_file($file, Yii::$app->basePath.'\web\files\task\\'.$model->id.DIRECTORY_SEPARATOR.$files['name'][$key])){
                                Yii::$app->session->setFlash('success', 'Файл не сохранён');
                            }

                        }
                    endforeach;
                return $this->redirect(['view-developer', 'id' => $model->id]);
            }
        }

        return $this->render('create-task-developer', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDone(){
        $id = Yii::$app->user->id;
        $tasks = Task::find()->where(['user_id' => $id])->all();
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
