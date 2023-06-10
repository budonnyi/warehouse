<?php

namespace app\controllers;

use app\models\Customer;
use app\models\Product;
use app\models\Select;
use Yii;
use app\models\Invoice;
use app\models\InvoiceSearchOld;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
                        'actions' => ['index', 'sale', 'create', 'update', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
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
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearchOld();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSale()
    {
        $searchModel = new InvoiceSearchOld();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        $dataProvider->query->andWhere(['document_type' => 'sale', 'store' => 'main']);

//        echo '<pre>';
//        foreach ($dataProvider->getModels() as $model) {
//            print_r($model->items);
//        }
//        die;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {
        $searchModel = new InvoiceSearchOld();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStore()
    {
        // date init
        if (!Yii::$app->session->has('start-date')) {
            Yii::$app->session->set('start-date', date('Y-m-d', strtotime(Invoice::find()->min('date'))));
            Yii::$app->session->set('end-date', date('Y-m-d', strtotime(Invoice::find()->max('date'))));
        }
        if ($request = Yii::$app->request->post()) {
            if (isset($request['Select']['date'])) {
                list($startDate, $endDate) = explode(' - ', $request['Select']['date']);
                Yii::$app->session->set('start-date', date('Y-m-d', strtotime($startDate)));
                Yii::$app->session->set('end-date', date('Y-m-d', strtotime($endDate)));
            }
        }
        $startDate = Yii::$app->session->get('start-date');
        $endDate = Yii::$app->session->get('end-date');

        $searchModel = new InvoiceSearchOld();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        $dataProvider->query->andWhere(['between', 'date', $startDate, $endDate]);

        $productList = ArrayHelper::map(Product::find()->all(), 'id', 'name');
        $productData = array();

        foreach ($dataProvider->getModels() as $item) {
            if ($item->transfer_type == 'income') {
                @$productData[$item->product_id]['quantity'] += $item->quantity;
                @$productData[$item->product_id]['proffit'] -= $item->price;
                @$productData[$item->product_id]['income_quantity'] += $item->quantity;
                @$productData[$item->product_id]['income_price'] += $item->price * $item->quantity;
            }
            if ($item->transfer_type == 'sale') {
                @$productData[$item->product_id]['quantity'] -= $item->quantity;
                @$productData[$item->product_id]['proffit'] += $item->price;
                @$productData[$item->product_id]['sale_quantity'] += $item->quantity;
                @$productData[$item->product_id]['sale_price'] += $item->price * $item->quantity;
            }
        }

        $amount = array();
        foreach ($productData as $key => $item) {
            @$store[$key]['product'] = $productList[$key];
            @$store[$key]['income_quantity'] = $item['income_quantity'] ?? 0;
            @$store[$key]['income_amount'] = number_format($item['income_price'] ?? 0, 2, ',', ' ');
            @$store[$key]['averige_price'] = number_format(round($item['income_price'] / ($item['income_quantity'] ?? 1), 2), 2, ',', ' ');
            @$store[$key]['sale_quantity'] = $item['sale_quantity'] ?? 0;
            @$store[$key]['sale_amount'] = number_format($item['sale_price'] ?? 0, 2, ',', ' ');
            @$store[$key]['store_quantity'] = $item['quantity'];
            @$store[$key]['store_amount'] = number_format(round(($item['income_price'] / ($item['income_quantity'] ?? 1)) * $item['quantity'], 2), 2, ',', ' ');
            @$store[$key]['proffit'] = number_format(($item['sale_price'] ?? 0) - ($item['sale_quantity'] ?? 0) * round($item['income_price'] / ($item['income_quantity'] ?? 1), 2), 2, ',', ' ');

            @$amount['income'] += $item['income_price'];
            @$amount['sale'] += $item['sale_price'] ?? 0;
            @$amount['store'] += round(($item['income_price'] / ($item['income_quantity'] ?? 1)) * $item['quantity'], 2);
            @$amount['proffit'] += ($item['sale_price'] ?? 0) - ($item['sale_quantity'] ?? 0) * round($item['income_price'] / ($item['income_quantity'] ?? 1), 2);
        }

//        echo '<pre>';
//        print_r($productData);
//        print_r($store);
//        die;

        $modelSelect = new Select();

        return $this->render('store', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'productList' => $productList,
            'productData' => $productData,
            'modelSelect' => $modelSelect,
            'store' => $store,
            'amount' => $amount,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $count = count(Yii::$app->request->post('Invoice', []));
        $model = [new Invoice()];

        for($i = 1; $i < $count; $i++) {
            $model[] = new Invoice();
        }

        if (Model::loadMultiple($model, Yii::$app->request->post()) && Model::validateMultiple($model)) {

            foreach ($model as $item) {
                if(!empty($item->newCustomer)) {
                    $customer = new Customer();
                    $customer->name = $item->newCustomer;
                    $customer->save(false);
                }
                $item->invoice = $invoice ?? $item->invoice;
                $invoice = $item->invoice;
                $item->customer_id = $customer->id ?? $customer_id ?? $item->customer_id;
                $customer_id = $item->customer_id;
                $item->transfer_type = $transfer_type ?? $item->transfer_type;
                $transfer_type = $item->transfer_type;
                $item->date = $date ?? $item->date;
                $date = $item->date;
                $item->store = $store ?? $item->store;
                $store = $item->store;
                $item->save(false);
            }
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model, 'count' => $count
        ]);
    }

    /**
     * Updates an existing Invoice model.
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

    public function actionAdd()
    {
        $cnt = Yii::$app->request->post('cnt');

        return $this->renderAjax('_add_form', [
            'cnt' => $cnt+1,
            'model' => new Invoice()
        ]);
    }

    /**
     * Deletes an existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
