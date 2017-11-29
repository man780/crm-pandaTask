<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use app\models\Execution;
use app\models\ExecutionSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExecutionController implements the CRUD actions for Execution model.
 */
class ExecutionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['execute'],
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
     * Lists all Execution models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExecutionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAccept($execution_id)
    {
        if(Yii::$app->user->identity->role == 1){
            $execution = Execution::findOne(['id' => $execution_id]);
            $execution->status = true;
            if($execution->save()){
                $task = $execution->task;
                $users = $task->users;
                $userCount = count($users);
                $executions = $task->executions;
                $execCount = 0;
                foreach($executions as $exec){
                    if($exec->status){
                        $execCount++;
                    }
                }
                if($execCount == $userCount){
                    $task->status = 15;
                    if(!$task->save()){
                        vd($task->errors);
                    }
                }
                $str = '';
                if($execution->task->to == 1){
                    $str = '-copy';
                }elseif ($execution->task->to == 2){
                    $str = '-translator';
                }elseif($execution->task->to == 3) {
                    $str = '-developer';
                }
                return $this->redirect(['/task/view'.$str, 'id' => $execution->task_id]);
            }else{
                vd($execution->errors);
            }
        }else{
            throw new HttpException(403);
        }
    }

    public function actionReject($execution_id)
    {
        if(Yii::$app->user->identity->role == 1){
            $execution = Execution::findOne(['id' => $execution_id]);
            $execution->status = false;
            if($execution->save()){
                $task = $execution->task;
                $task->status = 3;
                if($task->save()){
                    $str = '';
                    if($execution->task->to == 1){
                        $str = '-copy';
                    }elseif ($execution->task->to == 2){
                        $str = '-translator';
                    }elseif($execution->task->to == 3) {
                        $str = '-developer';
                    }
                    return $this->redirect(['/task/view'.$str, 'id' => $execution->task_id]);
                }else{
                    $task->errors;
                }

            }else{
                vd($execution->errors);
            }
        }else{
            throw new HttpException(403);
        }
    }

    /**
     * Creates a new Execution model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionExecute($task_id)
    {
        $model = new Execution();
        $model->task_id = (int) $task_id;
        $model->user_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            $model->time = time();
            $model->count_word = str_word_count(strip_tags ($model->text));
            $task = Task::findOne(['id' => $task_id]);
            $task->status=5;
            if(!$task->save()){
                vd($task->errors);
            }
            //vd($model->attributes);
            if($model->save()){
                $str = '';
                if($model->task->to == 1){
                    $str = '-copy';
                }elseif ($model->task->to == 2){
                    $str = '-translator';
                }else {
                    $str = '-developer';
                }

                return $this->redirect(['/task/view'.$str, 'id' => $model->task_id]);
            }else{
                vd($model->errors);
            }

        }else{
            return $this->render('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Displays a single Execution model.
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
     * Creates a new Execution model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Execution();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Execution model.
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
     * Deletes an existing Execution model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Execution model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Execution the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Execution::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
