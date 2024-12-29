<?php

namespace app\controllers;

use app\models\Attachment;
use app\models\Customer;
use app\models\InvoiceItem;
use app\models\Payment;
use app\models\PaymentSearch;
use app\models\Product;
use app\models\Select;
use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use Mpdf\Mpdf;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
                        'actions' => ['index', 'sale', 'create', 'update', 'view', 'bill-pdf', 'invoice-pdf', 'add', 'check',
                            'add-payment', 'add-import', 'add-import-payment', 'delete', 'erase',
                            'ajax-invoice-update', 'store', 'deletepayment'],
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
     * Lists all Invoice models.
     *
     * @return string
     */
    public function actionIndex($action, $dates = null)
    {
        $selects = $action;
//        if (in_array($action, ['sale', 'bill'])) $selects = ['sale', 'bill'];
//var_dump($this->request->queryParams);die;
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if ($selects == ['bill']) {
            $dataProvider->setSort(['defaultOrder' => ['bill_date' => SORT_DESC, 'bill' => SORT_DESC, 'date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        } else if ($action == 'income') {
            $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'bill_date' => SORT_DESC], 'enableMultiSort' => true]);
        } else if ($action == 'sale') {
            $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        } else if ($action == 'office') {
            $dataProvider->setSort(['defaultOrder' => ['bill_date' => SORT_DESC, 'date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        } else if ($action == 'import') {
            $dataProvider->setSort(['defaultOrder' => ['order_date' => SORT_DESC, 'bill' => SORT_DESC, 'bill_date' => SORT_DESC, 'invoice' => SORT_DESC, 'date' => SORT_DESC], 'enableMultiSort' => true]);
        } else if ($action == 'rejected') {
            $dataProvider->setSort(['defaultOrder' => ['order_date' => SORT_DESC, 'bill' => SORT_DESC, 'bill_date' => SORT_DESC, 'invoice' => SORT_DESC, 'date' => SORT_DESC], 'enableMultiSort' => true]);
        }
        if ($action == 'rejected') {
            $dataProvider->query->andWhere(['status' => 0, 'store' => 'main']);
        } else {
            $dataProvider->query->andWhere(['document_type' => $selects, 'store' => 'main', 'status' => [1, 2, 3, 4, 5, 6, 7, 8, 9]]);
        }
        if (!empty($dates)) {
            $date = explode(';', $dates);
            $dataProvider->query->andWhere(['between', 'date', $date[0], $date[1]]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'action' => $action
        ]);
    }

    public function actionCheck()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = false;
        $dataProvider->query->andWhere(['document_type' => ['sale', 'income', 'import', 'bill'], 'store' => 'main', 'status' => [1]]);

        $storeItems = [];

        $productsArray = ArrayHelper::map(Product::find()->all(), 'id', 'name');

        $invoiceItems = Invoice::find()->where(['status' => 1])->all();
//        $result = [];
//        foreach ($invoiceItems as $item) {
//            if (empty($item->invoices)) {
//                @$result[$item->invoice_id][] = [$item->id, $item->products->name, $item->quantity, $item->price];
//            }
//        }

        foreach ($invoiceItems as $invoiceModel) {
            foreach ($invoiceModel->items as $item) {
                @$storeItems[$item->product_id]['product_name'] = $productsArray[$item->product_id];
                if ($invoiceModel->document_type == 'income' || $invoiceModel->document_type == 'import') {
                    @$storeItems[$item->product_id]['onStoreQuantity'] += $item->quantity;
                    @$storeItems[$item->product_id]['income'] += $item->quantity;
                } else if ($invoiceModel->document_type == 'sale') {
                    @$storeItems[$item->product_id]['onStoreQuantity'] -= $item->quantity;
                    @$storeItems[$item->product_id]['sold'] += $item->quantity;
                    @$storeItems[$item->product_id]['profit'] += $item->price;
                } else if ($invoiceModel->document_type == 'bill') {
                    @$storeItems[$item->product_id]['orderedQuantity'] += $item->quantity;
                    @$storeItems[$item->product_id]['sold'] -= $item->quantity;
                }
            }
        }

        return $this->render('check', [
            'storeItems' => $storeItems,
        ]);
    }

    public function actionStore()
    {
        $storeItems = [];

        $productsArray = ArrayHelper::map(Product::find()->all(), 'id', 'name');
// where(['invoice.status' => 1])->
        $invoiceItems = InvoiceItem::find()->joinWith(['invoices'])->orderBy(['invoice.date' => SORT_DESC])->all();

        foreach ($invoiceItems as $item) {
            if (!$item->service) {
                @$storeItems[$item->product_id]['product_name'] = $productsArray[$item->product_id];
                if (in_array($item->invoices->document_type, ['income', 'import'])) {
                    if ($item->invoices->status == 1) {
                        @$storeItems[$item->product_id]['onStoreQuantity'] += $item->quantity ?? 0;
                        @$storeItems[$item->product_id]['income'] += $item->quantity;
                    } else {
                        @$storeItems[$item->product_id]['orderedQuantity'] += $item->quantity ?? 0;
//                        @$storeItems[$item->product_id]['income'] += $item->quantity;
                    }
                } else if ($item->invoices->document_type == 'sale' && $item->invoices->status == 1) {
                    @$storeItems[$item->product_id]['onStoreQuantity'] -= $item->quantity ?? 0;
                    @$storeItems[$item->product_id]['sold'] += $item->quantity;
                    @$storeItems[$item->product_id]['profit'] += $item->price * $item->quantity;
                } else if ($item->invoices->document_type == 'bill' && $item->invoices->status !== 3 && $item->invoices->status !== 0) {
                    @$storeItems[$item->product_id]['billedQuantity'] += $item->quantity;
                    @$storeItems[$item->product_id]['sold'] -= $item->quantity;
                }
                
                if (in_array($item->invoices->document_type, ['sale', 'bill']) && $item->invoices->status == 3) {
                    @$storeItems[$item->product_id]['payed'] += $item->quantity;
                }
            }
        }

        ksort($storeItems);

        return $this->render('store', [
            'storeItems' => $storeItems,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $paymentDataProvider = new ActiveDataProvider([
            'query' => Payment::find()->where(['invoice_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
        ]);
        $productDataProvider = new ActiveDataProvider([
            'query' => InvoiceItem::find()->where(['invoice_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
//        $searchModel = new PaymentSearch();
//        $dataProvider = $searchModel->search($this->request->queryParams);
//        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'id' => SORT_DESC], 'enableMultiSort' => true]);
//        $dataProvider->query->andWhere(['invoice_id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'paymentDataProvider' => $paymentDataProvider,
            'productDataProvider' => $productDataProvider,
        ]);
    }

    public function actionBillPdf($id)
    {
        $mpdf = new mPDF();
        $model = $this->findModel($id);

        $mpdf->WriteHTML($this->renderPartial('_bill-pdf', [
            'model' => $model,
        ]));
        $file_name = 'РФ' . $model->bill . '.pdf';
        $mpdf->Output($file_name, 'D');
        exit;
    }

    public function actionInvoicePdf($id)
    {
        $mpdf = new mPDF();
        $model = $this->findModel($id);

        $mpdf->WriteHTML($this->renderPartial('_invoice-pdf', [
            'model' => $model,
        ]));
        $file_name = 'ВН' . $model->invoice . '.pdf';
        $mpdf->Output($file_name, 'D');
        exit;
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate($action)
    {
        $invoiceModel = new Invoice();
        $invoiceModel->document_type = $action;
        $invoiceModel->store = 'main';
        $invoiceModel->status = 9;

        if ($action == 'bill') {
            $i = 1;
            $date = str_replace('.', '', date('d.m.Y'));
            do {
                $model = Invoice::findOne(['bill' => $date]);
                $invoiceModel->bill = $date;
                $invoiceModel->bill_date = date('Y-m-d');
                $date = $date . '/' . $i;
                $i++;
            } while (!empty($model));
        }

        if ($action == 'sale') {
            $maxInvioceId = Invoice::find()->where(['status' => 1])->andWhere(['document_type' => 'sale'])->select('invoice')->column();
            $invoiceModel->invoice = max($maxInvioceId) + 1;
            $invoiceModel->date = date('Y-m-d');
        }

        $itemModels = [new InvoiceItem()];
        $paymentModels = [new Payment()];

        if (!empty(Yii::$app->request->post())) {
//            echo '<pre>';
//            print_r($_POST);
//            die;
            if ($invoiceModel->load(Yii::$app->request->post())) {
                if (!empty($invoiceModel->newCustomer)) {
                    $customer = Customer::findOne(['name' => $invoiceModel->newCustomer]) ?? new Customer();
                    $customer->name = $invoiceModel->newCustomer;
                    $customer->type = $invoiceModel->document_type == 'sale' || $invoiceModel->document_type == 'bill' ? 'customer' : 'supplier';
                    $customer->status = 1;
                    $customer->save(false);

                    $invoiceModel->customer_id = $customer->id;
                }

                $invoiceModel->date = !empty($invoiceModel->date) ? date('Y-m-d', strtotime($invoiceModel->date)) : null;
                $invoiceModel->date_customs = !empty($invoiceModel->date_customs) ? date('Y-m-d', strtotime($invoiceModel->date_customs)) : null;
                $invoiceModel->bill_date = !empty($invoiceModel->bill_date) ? date('Y-m-d', strtotime($invoiceModel->bill_date)) : null;
                $invoiceModel->contract_date = !empty($invoiceModel->contract_date) ? date('Y-m-d', strtotime($invoiceModel->contract_date)) : null;

                $invoiceModel->save(false);
            }

            $totalAmount = $totalPayedAmount = $totalInvoiceQuantity = 0;
            if (!empty(Yii::$app->request->post('Payment')))
                foreach (Yii::$app->request->post('Payment') as $item) {
                    if (empty($item['date']) || empty($item['description']) || empty($item['amount']))
                        continue;
                    $paymentModel = new Payment();

                    $paymentModel->attributes = $item;
//                    echo '<pre>';
//                    print_r($paymentModel->attributes);
//                    die;
                    $paymentModel->date = !empty($paymentModel->date) ? date('Y-m-d', strtotime($paymentModel->date)) : null;
                    $paymentModel->invoice_id = $invoiceModel->id;
                    $paymentModel->currency = $paymentModel->currency ?? 'uah';
                    $paymentModel->direction = $paymentModel->direction ?? (($invoiceModel->document_type == 'income' || $invoiceModel->document_type == 'import') ? 'payment' : 'income');
                    $paymentModel->customer_id = $paymentModel->customer_id ?? '';
                    $paymentModel->status = true;

                    if ($paymentModel->currency == 'uah')
                        @$totalPayedAmount += $paymentModel->amount ?? 0;
                    $paymentModel->save(false);

                    if ($item['currency'] == 'uah')
                        @$totalPayedAmount += $item['amount'] ?? 0;
                    $paymentModel->save(false);
                }

            if (!empty(Yii::$app->request->post('InvoiceItem')))
                foreach (Yii::$app->request->post('InvoiceItem') as $item) {
                    $itemModel = new InvoiceItem();

                    $itemModel->attributes = $item;

                    if (!empty($item['product_id'])) {
                        $productModel = Product::findOne(['id' => $item['product_id']]);
                    } else if (!empty($item['new_product'])) {
                        $productModel = Product::findOne(['name' => $item['new_product']]);

                        if (empty($productModel))
                            $productModel = new Product();

                        $productModel->name = $item['new_product'];
                        $productModel->status = 1;
                        $productModel->articul = !empty($item['articul']) ? $item['articul'] : ($productModel->articul ?? '');
                        $productModel->save(false);
                    } else {
                        continue;
                    }

                    if (!empty($itemModel->articul)) {
                        $productModel->articul = !empty($itemModel->articul) ? $itemModel->articul : ($productModel->articul ?? '');
                    }

                    $productModel->save(false);

                    $itemModel->invoice_id = $invoiceModel->id;
                    $itemModel->product_id = $productModel->id;

                    if ($invoiceModel->document_type == "import" && empty($itemModel->service)) {
                        $percentWeight = !empty($invoiceModel->total_sek_amount) ? ($itemModel->total_sek / $invoiceModel->total_sek_amount) : 0;
                        if (!empty($totalAmount))
                            $itemModel->price = $totalAmount * $percentWeight / $itemModel->quantity;
                    } else {
                        $itemModel->price = $itemModel->price ?? 0;
                        @$totalAmount += $itemModel->price * $itemModel->quantity ?? 0;
                    }

                    @$totalInvoiceQuantity += $itemModel->quantity;
                    $itemModel->save(false);
                }

            $invoiceModel->total_amount = !(empty($invoiceModel->total_amount)) ? $invoiceModel->total_amount : $totalAmount;
            $invoiceModel->total_amount_sek = $invoiceModel->total_sek_amount ?? 0;

            $invoiceModel->quantity = $totalInvoiceQuantity;
            $invoiceModel->save();

            return $this->redirect(['index', 'action' => $invoiceModel->document_type]);
        }

        return $this->render('create', ['invoiceModel' => $invoiceModel, 'itemModels' => $itemModels, 'count' => 0, 'action' => $action, 'paymentModels' => $paymentModels]);
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $invoiceModel = $this->findModel($id);
        $itemModels = InvoiceItem::find()->where(['invoice_id' => $id])->indexBy('id')->all();
        $paymentModels = Payment::find()->where(['invoice_id' => $id])->indexBy('id')->orderBy(['date' => SORT_ASC])->all();

        if (!empty(Yii::$app->request->post())) {
//            echo '<pre>';
//            print_r($_POST);
//            die;
            if ($invoiceModel->load(Yii::$app->request->post())) {
                if (!empty($invoiceModel->newCustomer)) {
                    $customer = Customer::findOne(['name' => $invoiceModel->newCustomer]);
                    if (empty($customer))
                        $customer = new Customer();
                    $customer->name = $invoiceModel->newCustomer;
                    $customer->type = $invoiceModel->document_type == 'sale' ? 'customer' : 'supplier';
                    $customer->status = true;
                    $customer->save(false);

                    $invoiceModel->customer_id = $customer->id;
                }

                $invoiceModel->date = !empty($invoiceModel->date) ? date('Y-m-d', strtotime($invoiceModel->date)) : null;
                $invoiceModel->date_customs = !empty($invoiceModel->date_customs) ? date('Y-m-d', strtotime($invoiceModel->date_customs)) : null;
                $invoiceModel->bill_date = !empty($invoiceModel->bill_date) ? date('Y-m-d', strtotime($invoiceModel->bill_date)) : null;
                $invoiceModel->contract_date = !empty($invoiceModel->contract_date) ? date('Y-m-d', strtotime($invoiceModel->contract_date)) : null;

                if (!empty($invoiceModel->document_type)
                    && $invoiceModel->document_type == 'bill'
                    && !empty($invoiceModel->invoice)
                    && !empty($invoiceModel->date)
                ) $invoiceModel->document_type = 'sale';
                $invoiceModel->save(false);
            }

            $totalAmount = $totalPayedAmount = $totalInvoiceQuantity = 0;
            if (!empty(Yii::$app->request->post('Payment')))
                foreach (Yii::$app->request->post('Payment') as $item) {
                    if (empty($item['date']) || empty($item['description']) || empty($item['amount']))
                        continue;
                    $paymentModel = Payment::findOne(['id' => $item['id'], 'invoice_id' => $id]);

                    if (empty($paymentModel) && !empty($item['amount']))
                        $paymentModel = new Payment();

                    $paymentModel->attributes = $item;
//                    echo '<pre>';
//                    print_r($paymentModel->attributes);
//                    die;
                    $paymentModel->date = !empty($paymentModel->date) ? date('Y-m-d', strtotime($paymentModel->date)) : null;
                    $paymentModel->invoice_id = $id;
                    $paymentModel->currency = $paymentModel->currency ?? 'uah';
                    $paymentModel->direction = $paymentModel->direction ?? (($invoiceModel->document_type == 'income' || $invoiceModel->document_type == 'import') ? 'payment' : 'income');
                    $paymentModel->customer_id = $paymentModel->customer_id ?? '';
                    $paymentModel->status = true;

                    if ($paymentModel->currency == 'uah')
                        @$totalPayedAmount += $paymentModel->amount ?? 0;
                    $paymentModel->save(false);
                }

//            echo '<pre>';
//            print_r($totalPayedAmount);
//            print_r(Yii::$app->request->post());
//            die;
            if (!empty(Yii::$app->request->post('InvoiceItem')))
                foreach (Yii::$app->request->post('InvoiceItem') as $item) {
                    $itemModel = InvoiceItem::findOne(['id' => $item['id'], 'invoice_id' => $id]);
                    if (empty($itemModel))
                        $itemModel = new InvoiceItem();

                    $itemModel->attributes = $item;

                    if (!empty($item['product_id'])) {
                        $productModel = Product::findOne(['id' => $item['product_id']]);
                    } else if (!empty($item['new_product'])) {
                        $productModel = Product::findOne(['name' => $item['new_product']]);

                        if (empty($productModel))
                            $productModel = new Product();

                        $productModel->name = $item['new_product'];
                        $productModel->status = true;
                        $productModel->articul = !empty($item['articul']) ? $item['articul'] : ($productModel->articul ?? '');
                        $productModel->save(false);
                    } else {
                        continue;
                    }

                    if (!empty($itemModel->articul)) {
                        $productModel->articul = !empty($itemModel->articul) ? $itemModel->articul : ($productModel->articul ?? '');
                    }

                    $productModel->save(false);

                    $itemModel->invoice_id = $invoiceModel->id;
                    $itemModel->product_id = $productModel->id;
//var_dump($invoiceModel->total_sek_goods_amount);
//var_dump($itemModel->total_sek);
//var_dump($invoiceModel->total_sek_goods_amount);
//var_dump(!empty($invoiceModel->total_sek_goods_amount) ? ($itemModel->total_sek / $invoiceModel->total_sek_goods_amount) : 0);die;
                    if ($invoiceModel->document_type == "import") {
                        if (empty($itemModel->service)) {
                            $percentWeight = !empty($invoiceModel->total_sek_goods_amount) ? ($itemModel->total_sek / $invoiceModel->total_sek_goods_amount) : 0;
                            if (!empty($totalPayedAmount))
                                $itemModel->price = $totalPayedAmount * $percentWeight / $itemModel->quantity;
                        } else {
                            $itemModel->price = 0;
                        }
                    } else {
                        $itemModel->price = $itemModel->price ?? 0;
                        @$totalAmount += $itemModel->price * $itemModel->quantity ?? 0;
                    }

                    @$totalInvoiceQuantity += $itemModel->quantity;
                    $itemModel->save();
                }

            $invoiceModel->total_amount = !(empty($invoiceModel->total_amount)) ? $invoiceModel->total_amount : $totalAmount;
            $invoiceModel->total_amount_sek = $invoiceModel->total_sek_amount ?? 0;

            $invoiceModel->quantity = $totalInvoiceQuantity;
            $invoiceModel->update();

            return $this->redirect(['index', 'action' => $invoiceModel->document_type]);
        }

        return $this->render('update', ['invoiceModel' => $invoiceModel, 'itemModels' => $itemModels, 'paymentModels' => $paymentModels, 'count' => count($itemModels)]);
    }

    public function actionAdd()
    {
        $cnt = Yii::$app->request->post('cnt');

        return $this->renderAjax('_add_form', [
            'cnt' => $cnt + 1,
            'model' => new InvoiceItem()
        ]);
    }

    public function actionAddPayment()
    {
        $cnt = Yii::$app->request->post('cnt');

        return $this->renderAjax('_add_payment_form', [
            'cnt' => $cnt + 1,
            'model' => new Payment()
        ]);
    }

    public function actionAjaxInvoiceUpdate()
    {
        $invoiceId = Yii::$app->request->post('invoiceId');
        $status = Yii::$app->request->post('status');
        $model = Invoice::findOne(['id' => $invoiceId]);
        $model->status = $status;
        $model->save(false);

        return true;
    }

    public function actionAddImportPayment()
    {
        $cnt = Yii::$app->request->post('cnt');

        return $this->renderAjax('_add_import_payment_form', [
            'cnt' => $cnt + 1,
            'model' => new Payment()
        ]);
    }

    public function actionAddImport()
    {
        $cnt = Yii::$app->request->post('cnt');
        $service = Yii::$app->request->post('service', false);

        return $this->renderAjax('_add_form_import', [
            'cnt' => $cnt + 1,
            'service' => !empty($service),
            'model' => new InvoiceItem()
        ]);
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Invoice::findOne($id);
        $action = $model->document_type;
        $model->delete();

        InvoiceItem::deleteAll(['invoice_id' => $id]);
        Payment::deleteAll(['invoice_id' => $id]);
        $attachments = Attachment::findAll(['entity_id' => $id, 'entity_type' => Attachment::INVOICE]);
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                @unlink(Yii::getAlias('@app') . '/web/files/' . $attachment->filename);
//            $attachment->delete();
            }
            Attachment::deleteAll(['invoice_id' => $id]);
        }

        return $this->redirect(['index', 'action' => $action]);
    }

    public function actionErase()
    {
        $id = Yii::$app->request->post('id');
        $invoiceId = Yii::$app->request->post('invoiceId');
        $model = InvoiceItem::findOne(['id' => $id, 'invoice_id' => $invoiceId]);
        if ($model->delete()) {
            echo json_encode(true);
        } else {
            json_encode(false);
        }
    }

    public function actionDeletepayment()
    {
        $id = Yii::$app->request->post('id');
        $invoiceId = Yii::$app->request->post('invoiceId');
        $model = Payment::findOne(['id' => $id, 'invoice_id' => $invoiceId]);
        if ($model->delete()) {
            echo json_encode(true);
        } else {
            json_encode(false);
        }
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
