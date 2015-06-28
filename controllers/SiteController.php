<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\helpers\Url;
use app\models\ValidarFormulario;
use app\models\ValidarFormularioAjax;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\models\FormAlumnos;
use app\models\Alumnos;

class SiteController extends Controller
{   
    /**
     * 
     * @return type
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Pagina de ejemplo para empezar esta mierda
     * @return type
     */
    public function actionSaluda($persona = "Jaime"){
        return $this->render("saluda",['persona'=>$persona]);
    }
    /**
     * 
     * @return type
     */
    public function actionFormulario(){
        return $this->render("formulario");
    }
    /**
     * 
     */
    public function actionRequest(){
        $mensaje = null;
        if (isset($_REQUEST['nombre']) and !empty($_REQUEST['nombre'])) {
            $mensaje = "Bien has enviado tu nombre correctamente ".$_REQUEST['nombre'];
            \Yii::$app->getSession()->setFlash('success', $mensaje);
        }else{
            $mensaje = "No has enviado ningun nombre, asegurate de enviarlo";
            \Yii::$app->getSession()->setFlash('danger', $mensaje);
        }
        
        $this->redirect(Url::toRoute("site/formulario"));
    }
    
    public function actionValidarformulario(){
        $model = new ValidarFormulario();
        if ($model->load(Yii::$app->request->post())) 
        {
            if ($model->validate()) 
            {
                //por ejemplo consultar en una base de datos
                $mensaje = "Paso";
                \Yii::$app->getSession()->setFlash('success', $mensaje);
            }else
            {
                $model->getErrors();
            }
        }
        return $this->render("validarformulario",['model'=>$model]);
    }
    public function actionValidarformularioajax(){
        $model = new ValidarFormularioAjax();
        $msg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $msg = "Enhora buena formulario enviado correctamente";
                \Yii::$app->getSession()->setFlash('success', $msg);
                $model->nombre = null;
                $model->email = null;
            }else{
                $model->getErrors();
            }
        }
        return $this->render('validarformularioajax',['model'=>$model]);
    }
    public function actionCreate()
    {
        $model = new FormAlumnos();
        $msg = null;
        if ($model->load(Yii::$app->request->post())) 
        {
            if ($model->validate()) {
                $table = new Alumnos();
                $table->nombre = $model->nombre;
                $table->apellidos = $model->apellidos;
                $table->clase = $model->clase;
                $table->nota_final = $model->nota_final;
                if ($table->insert()) {
                    $msg="Registros guardados exitosamente!";
                    $model->nombre = null;
                    $model->apellidos = null;
                    $model->clase = null;
                    $model->nota_final = null;
                    \Yii::$app->getSession()->setFlash('success',$msg);
                }else{
                    $msg = "Ha ocurrido un error al insertar el registro";
                    \Yii::$app->getSession()->setFlash('danger',$msg);
                }
            }else{
                $model->getErrors();
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    /**
     * 
     * @return type
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * 
     * @return type
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    /**
     * 
     * @return type
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * 
     * @return type
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
    /**
     * 
     * @return type
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
