<?php

namespace app\controllers;

use app\models\CustomersStaff;
use app\models\Invoice;
use app\models\InvoiceItem;
use app\models\InvoiceSearch;
use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Payment;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use kartik\mpdf\Pdf;
use Mpdf\Mpdf;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'view',
                            'delete'],
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
                        'roles' => ["?", "@"],
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $selects = ['sale', 'bill'];

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['invoice' => SORT_DESC, 'date' => SORT_DESC], 'enableMultiSort' => true]);
        $dataProvider->query->andWhere(['customer_id' => $id, 'store' => 'main']);
        $paymentModels = Payment::find()->where(['customer_id' => $id])->orderBy(['date' => SORT_ASC])->all();

        $paymentDataProvider = new ActiveDataProvider([
            'query' => Payment::find()->where(['customer_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
        ]);

        $staffProvider = new ActiveDataProvider([
            'query' => CustomersStaff::find()->where(['customer_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_DESC,
                ]
            ],
        ]);
//        $productDataProvider = new ActiveDataProvider([
//            'query' => InvoiceItems::find()->where(['customer_id' => $id])->with(['items']),
//            'sort' => [
//                'defaultOrder' => [
//                    'id' => SORT_ASC,
//                ]
//            ],
//        ]);

//        echo '<pre>';
//        print_r($productDataProvider->getModels());
//        die;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'paymentModels' => $paymentModels,
            'paymentDataProvider' => $paymentDataProvider,
            'staffProvider' => $staffProvider,
//            'productDataProvider' => $productDataProvider,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
