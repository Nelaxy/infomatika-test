<?php

namespace app\controllers;

use app\models\ActivationCode;
use app\models\RegistrationForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'logout', 'profile', 'delete-profile'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['profile', 'delete-profile', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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
     * Sends logged user to their profile page.
     * Shows registration form and receives user data from it,
     * tries to save it, after saving sends mail on User's email.
     * Redirects to Login form.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        if (Yii::$app->user->getId()) {
            $this->redirect(Url::to('profile'));
        }
        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->signup()) {
                    throw new \Exception('Не удалось зарегистрироваться!');
                }
                $transaction->commit();
                $this->redirect(Url::to(['login', 'reg' => 1]));
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->generateJson(null, 0, $e->getMessage());
            }
        }
        return $this->render('index', [
            'model' => $model,
        ]);

    }


    /**
     * Login action.
     * @param int $reg for determine if login page redirected from registration page
     * @return mixed
     */
    public function actionLogin(int $reg = 0)
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->redirect(Url::to('profile'));
        }
        return $this->render('login', [
            'model' => $model,
            'reg' => $reg
        ]);

    }

    /**
     * Action receives activation code sent to the User E-mail,
     * tries to activate users profile
     * and redirects to user profile page.
     * @param $activation_code
     * @return mixed
     */
    public function actionActivateProfile($activation_code)
    {
        $model = ActivationCode::findOne(['code' => $activation_code]);
        if ($model) {
            $user = $model->user;
            $user->is_activated = true;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$user->save()) {
                    throw new \Exception('Не удалось активировать профиль пользователя!');
                }
                $transaction->commit();
                Yii::$app->user->login($user, 3600 * 24 * 30);
                $this->redirect(Url::to('profile'));
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->generateJson(null, 0, $e->getMessage());
            }
        }
        return $this->redirect(Url::to('index'));
    }

    /**
     * Action defines current logged user,
     * verifies user profile activation status:
     * if it isn't activate shows activation request
     * if it is activated, shows profile information
     * and redirects to user profile page.
     * @return mixed
     */
    public function actionProfile()
    {
        $model = User::findOne(['id' => Yii::$app->user->getId()]);
        if (!$model || !$model->is_activated) {
            return $this->render('activation-request');
        }
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new \Exception('Не сохранить изменения профиля!');
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->generateJson(null, 0, $e->getMessage());
            }
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(Url::to('index'));
    }

    /**
     * Defines current logged User and tries to delete his profile.
     *
     * @return mixed
     */
    public function actionDeleteProfile()
    {
        $model = User::findOne(['id' => Yii::$app->user->getId()]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->delete()) {
                throw new \Exception('Не удалось удалить профиль!');
            }
            $transaction->commit();
            $this->redirect(Url::to('index'));
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->generateJson(null, 0, $e->getMessage());
        }
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Общий формат ответа сервера:
     * {
     * "time": "2017-12-28 12:11:34",
     * "data": null,
     * "success": 1,
     * "message": ""
     * }
     * time - дата и время ответа сервера,
     * data - тело ответа, в случае ошибки null
     * success - удачно ли выполнился метод (1-удачно, 0 - нет)
     * message - текст ошибки, в случае если success=0
     *
     * @param $data
     * @param int $success
     * @param string $message
     * @return array
     */
    public function generateJson($data, $success = 1, $message = '')
    {
        if ($success == 0)
            \Yii::$app->response->setStatusCode(500);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'time' => date('Y-m-d H:i:s'),
            'data' => $data,
            'success' => $success,
            'message' => $message
        ];
    }


}
