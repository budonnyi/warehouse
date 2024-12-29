<?php

namespace app\controllers;

use app\models\Invoice;
use app\models\InvoiceItem;
use app\models\Payment;
use app\models\PaymentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use SimpleXLSX;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
//                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'erase', 'invoices', 'create-ajax', 'update-ajax',
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
     * Lists all Payment models.
     *
     * @return string
     */
    public function actionIndex($dates = null)
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'id' => SORT_DESC], 'enableMultiSort' => true]);
        if (!empty($dates)) {
            $date = explode(';', $dates);
            $dataProvider->query->andWhere(['between', 'date', $date[0], $date[1]]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImportPayments() {
        include 'simplexlsx.class.php';
//        $xlsx = new SimpleXLSX( 'countries_and_population.xlsx' );
        $xlsx = new SimpleXLSX('0000003106766874.xls');
        $data =  $xlsx->rows();
        echo '<pre>';
//        print_r($xlsx);
        print_r($data);
        echo '</pre>';
        die;

        $csv = '0000003119013568.csv';

        $csvContent = array();
        $lines = file($csv, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $value)
        {
            $csvContent[$key] = str_getcsv($value);
        }

        echo '<pre>';
        print_r($csvContent);
        echo '</pre>';
        die;
//        $csv = mb_convert_encoding($csv, 'UCS-2LE', 'UTF-8');
        $csvContent = str_getcsv(file_get_contents($csv));

        echo '<pre>';
//        print_r($csvContent);
//        echo '</pre>';
//        die;

        $file = fopen($csv, 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            //$line is an array of the csv elements
            print_r($line);
        }
        fclose($file);
die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Payment();

        if ($this->request->isPost) {

            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        $invoices = [];//$this->getInvoicesArr();

//        echo '<pre>';
//        print_r(($invoices));
//        die;

        return $this->render('create', [
            'model' => $model,
            'invoices' => $invoices
        ]);
    }

    public function actionCreateAjax()
    {
        $model = new Payment();
        $model->invoice_id = Yii::$app->request->post('invoice_id');
        $model->date = Yii::$app->request->post('date');
        $model->description = Yii::$app->request->post('description');
        $model->direction = Yii::$app->request->post('direction');
        $model->category_id = Yii::$app->request->post('category_id');
        $model->customer_id = Yii::$app->request->post('customer_id');
        $model->amount = Yii::$app->request->post('amount');
        $model->currency = Yii::$app->request->post('currency');
        $model->status = true;

        if ($model->save(false)) {
            echo json_encode(true);
        } else {
            json_encode(false);
        }
    }

    public function actionUpdateAjax()
    {
        $id = Yii::$app->request->post('id');
        $model = Payment::findOne(['id' => $id]);
        $model->date = Yii::$app->request->post('date');
        $model->description = Yii::$app->request->post('description');
        $model->direction = Yii::$app->request->post('direction');
        $model->category_id = Yii::$app->request->post('category_id');
        $model->customer_id = Yii::$app->request->post('customer_id');
        $model->amount = Yii::$app->request->post('amount');

        if ($model->save()) {
            echo json_encode(true);
        } else {
            json_encode(false);
        }
    }

    public function actionInvoices() {
        $direction = Yii::$app->request->post('direction');
        switch ($direction) {
            case 'payment':
                $type = 'sale';
                break;
            case 'income':
                $type = ['income', 'import'];
                break;
            default:
                $type = 'sale';
        }

        $invoices = [];
        $invoiceModels = Invoice::find()->where(['document_type' => $type])->with('customers')->all();
        foreach ($invoiceModels as $item) {
            $invoices[$item->id] = '[' . $item->invoice . ']' . ' ' . ($item->customers->name??'-');
        }

        return json_encode($invoices);
    }
    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $invoices = [];//$this->getInvoicesArr();

        return $this->render('update', [
            'model' => $model,
            'invoices' => $invoices
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionErase()
    {
        $id = Yii::$app->request->post('paymentId');
        $invoiceId = Yii::$app->request->post('invoiceId');
        $model = Payment::findOne(['id' => $id, 'invoice_id' => $invoiceId]);
        if ($model->delete()) {
            echo json_encode(true);
        } else {
            json_encode(false);
        }
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
