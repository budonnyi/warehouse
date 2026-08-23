<?php

namespace app\controllers;

use app\models\Invoice;
use app\models\InvoiceItem;
use app\models\InvoiceSearch;
use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                            'delete', 'bulk-update'],
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        $dataProvider->query->andWhere(['service' => null]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Bulk-saves changed products (AJAX, JSON response).
     */
    public function actionBulkUpdate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data   = Yii::$app->request->post('Product', []);
        $saved  = 0;
        $errors = [];

        foreach ($data as $id => $attributes) {
            $model = Product::findOne((int)$id);
            if (!$model) continue;
            $model->setAttributes($attributes);
            if ($model->save()) {
                $saved++;
            } else {
                $errors[$id] = $model->errors;
            }
        }

        return ['saved' => $saved, 'errors' => $errors];
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $models = InvoiceItem::find()->where(['product_id' => $id])->joinWith(['invoices'])->orderBy(['invoice.date' => SORT_DESC])->all(); // ->orderBy(['invoice.date' => SORT_DESC])

//        foreach ($models as $model) {
//            echo '<pre>';
//            print_r($model->invoices);
//            die;
//        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'models' => $models
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
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
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $invoiceIds = InvoiceItem::find()
            ->select('invoice_id')
            ->where(['product_id' => (int)$id])
            ->distinct()
            ->column();

        if (!empty($invoiceIds)) {
            $invoices = Invoice::find()
                ->select(['id', 'order_num', 'date'])
                ->where(['id' => $invoiceIds])
                ->asArray()
                ->all();

            Yii::$app->session->setFlash('productDeleteBlocked', [
                'name'     => $model->name ?: ($model->articul ?: "ID {$model->id}"),
                'invoices' => $invoices,
            ]);

            return $this->redirect(['index']);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
