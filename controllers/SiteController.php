<?php

namespace app\controllers;

use app\models\Invoice;
use app\models\InvoiceSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use kartik\mpdf\Pdf;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

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
//                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout', 'request-password-reset', 'index', 'reset-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['error'],
                        'allow' => true,
                        'roles' => ["?","@"],
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

    public function actionGetpdf($id) {
        $this->layout = 'pdf';
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        $result['sold'] = 0;
        $sold = 0;

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['between', 'date', "2023-01-01", "2023-12-31" ]);
        $dataProvider->query->andWhere(['document_type' => 'sale', 'store' => 'main', 'status' => true]);
        foreach ($dataProvider->getModels() as $invoiceModel) {
            @$summResult += $invoiceModel->total_amount;
        }
//        $model = $this->findModel($id);

        //$model = $this->findModel();
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $this->render('viewpdf', ['model'=>$model]),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.img-circle {border-radius: 50%;}',
            'options' => [
                'title' => $model->title,
                'subject' => 'PDF'
            ],
            'methods' => [
                'SetHeader' => ['<img src="/images/inspire2_logo_20.png" class="img-circle"> Школа брейк данса INSPIRE||inspire2.ru'],
                'SetFooter' => ['|{PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $filter['dateFrom'] = date('Y-m-d', strtotime('first day of january this year'));
        $filter['dateTo'] = date('Y-m-d');
        if (!empty($_POST)) {
            $filter['dateFrom'] = !empty($_POST['main_date_from']) ? $_POST['main_date_from'] : $filter['dateFrom'];
            $filter['dateTo'] = !empty($_POST['main_date_to']) ? $_POST['main_date_to'] : $filter['dateTo'];
        }

        $billsSearchModel = new InvoiceSearch();
        $billsProvider = $billsSearchModel->search($this->request->queryParams);
        $billsProvider->setSort(['defaultOrder' => ['bill_date' => SORT_DESC, 'date' => SORT_DESC,  'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        $billsProvider->query->andWhere(['status' => [2, 3, 4, 5, 6, 7, 9]]);

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['between', 'date', $filter['dateFrom'], $filter['dateTo'] ]);
//        $dataProvider->query->andWhere(['document_type' => 'sale']);
        $dataProvider->query->orderBy([
            'date' => SORT_DESC,
            'invoice'=>SORT_DESC
        ]);
        $dataProvider->query->andWhere(['document_type' => 'sale']);
        $dataProvider->pagination = false;

        $result = $graph = [];

        foreach ($dataProvider->getModels() as $invoiceModel) {

            @$graph[date('Y', strtotime($invoiceModel->date)) . '_' . date('m', strtotime($invoiceModel->date))] += $invoiceModel->total_amount;
            if ($invoiceModel->document_type == 'sale') {
                @$result['contracts']++;
                @$result['summResult'] += $invoiceModel->total_amount;
                @$result['productsResult'][$invoiceModel->status] += $invoiceModel->quantity;
                $amount = 0;
                foreach ($invoiceModel->payments as $payment) {
                    $amount += !empty($payment->amount) && $payment->currency == 'uah' ? $payment->amount : 0;
                }
                @$result['moneyReceived'][$invoiceModel->status] += $amount;
            }
            if ($invoiceModel->document_type == 'import') {
                @$result['import_deals'] += 1;
                @$result['import_amount'] += $invoiceModel->total_amount;
            }
        }
        ksort($graph);
//echo '<pre>';var_dump($graph);exit;
        return $this->render('index', compact('result', 'filter', 'dataProvider', 'billsProvider', 'searchModel', 'graph'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

//    public function actionAddAdmin()
//    {
//        $model = User::find()->where(['username' => 'admin'])->one();
//        if (empty($model)) {
//            $user = new User();
//            $user->username = 'admin';
//            $user->email = 'admin@кодер.укр';
//            $user->setPassword('admin');
//            $user->generateAuthKey();
//            if ($user->save()) {
//                echo 'good';
//            }
//        }
//    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
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

        return $this->goHome();
    }
}
