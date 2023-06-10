<?php

namespace app\controllers;

use app\models\Customer;
use app\models\InvoiceItem;
use app\models\Product;
use app\models\Select;
use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
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
                        'actions' => ['index', 'sale', 'create', 'update', 'view', 'add', 'delete'],
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
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSale()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        $dataProvider->query->andWhere(['document_type' => 'sale', 'store' => 'main']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['date' => SORT_DESC, 'invoice' => SORT_DESC], 'enableMultiSort' => true]);
        $dataProvider->query->andWhere(['document_type' => 'import', 'store' => 'main']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
//    public function actionCreate()
//    {
//        $model = new Invoice();
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }
    public function actionCreate()
    {
        $invoiceModel = new Invoice();
        $count = count(Yii::$app->request->post('InvoiceItem', []));
//        $itemModels = [new InvoiceItem()];
        for($i = 0; $i < $count; $i++) {
            $itemModels[] = new InvoiceItem();
        }

//        $itemModels = [new InvoiceItem()];

        if (Model::loadMultiple($itemModels, Yii::$app->request->post())
            && Model::validateMultiple($itemModels)
            && $invoiceModel->load($this->request->post())) {
//            echo '<pre>';
//            print_r(Yii::$app->request->post());
//            foreach ($itemModels as $itemModel) {
//
//            print_r($itemModel->attributes);
//            }
//            die;

            $invoiceModel->save();

            foreach ($itemModels as $item) {
                if (!empty($item->product_id)) {
                    $productModel = Product::findOne(['id' => $item->product_id]);
                } else if (!empty($item->new_product)) {
                    $productModel = new Product();
                    $productModel->articul = $item->articul;
                    $productodel->name = $item->new_product;
                    $productodel->status = 1;
                    $productodel->save();
                }

                if (empty($productModel)) {
                    continue;
                }

                if (!empty($item->articul))
                    $productodel->articul = $item->articul;
                $item->invoice_id = $invoiceModel->id;

                $item->save();
            }

            return $this->redirect('index');
        }

        return $this->render('create', ['invoiceModel' => $invoiceModel, 'itemModels' => $itemModels]);
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
        $itemModels = InvoiceItem::findAll(['invoice_id' => $id]);

//        echo '<pre>';
//        print_r($invoiceModel->attributes);
//        foreach ($itemModels as $item) {
//            print_r($item->attributes);
//        }
//        die;

        if (Model::loadMultiple($itemModels, Yii::$app->request->post())
            && Model::validateMultiple($itemModels)
            && $invoiceModel->load($this->request->post())) {
//            echo '<pre>';
//            print_r(Yii::$app->request->post());
//            print_r($invoiceModel->attributes)
//            die;

            $invoiceModel->update();

            foreach ($itemModels as $item) {
                if (!empty($item->product_id)) {
                    $productModel = Product::findOne(['id' => $item->product_id]);
                } else if (!empty($item->new_product)) {
                    $productModel = new Product();
                    $productModel->articul = $item->articul;
                    $productodel->name = $item->new_product;
                    $productodel->status = 1;
                    $productodel->save();
                }

                if (empty($productModel)) {
                    continue;
                }

                $productodel->articul = $item->articul ?? null;
                $item->invoice_id = $id;

                $item->save();
            }
        }

        return $this->render('update', ['invoiceModel' => $invoiceModel, 'itemModels' => $itemModels, 'count' => count($itemModels)]);
    }

    public function actionAdd()
    {
        $cnt = Yii::$app->request->post('cnt');

        return $this->renderAjax('_add_form', [
            'cnt' => $cnt+1,
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
        $this->findModel($id)->delete();
        InvoiceItem::deleteAll(['invoice_id' => $id]);

        return $this->redirect(['index']);
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
