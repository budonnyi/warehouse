<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\InvoiceItem;
use app\models\Proposal;
use app\models\ProposalProducts;
use app\models\ProposalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProposalController implements the CRUD actions for Proposal model.
 */
class ProposalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Proposal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProposalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Proposal model.
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
     * Creates a new Proposal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Proposal();
        $itemModels = [new ProposalProducts()];

        if (!empty(Yii::$app->request->post())) {
            if ($model->load(Yii::$app->request->post())) {
                if (!empty($model->newCustomer)) {
                    $customer = Customer::findOne(['name' => $model->newCustomer]) ?? new Customer();
                    $customer->name = $model->newCustomer;
                    $customer->type = $model->document_type == 'sale' ? 'customer' : 'supplier';
                    $customer->status = true;
                    $customer->save(false);

                    $model->customer_id = $customer->id;
                }

                $model->save(false);

                $totalProposalCost = 0;
                foreach (Yii::$app->request->post('ProposalProduct') as $item) {
                    if (!empty($item['product_id'])) {
                        $productModel = Product::findOne(['id' => $item['product_id']]);
                    } else if (!empty($item['new_product'])) {
                        $productModel = new Product();
                        $productModel->name = $item['new_product'];
                        $productModel->status = true;
                    } else {
                        continue;
                    }
                    $productModel->save(false);

                    $itemModel = new ProposalProducts();
                    $itemModel->product_id = $productModel->id;
                    $itemModel->quantity = $item['quantity'];
                    $itemModel->proposal_id = $model->id;

                    @$totalProposalAmount += floatval($itemModel->price) * intval($itemModel->quantity);

                    $itemModel->save();
                }

                $model->total_amount = $totalProposalAmount;
                $modal->update();

                return $this->redirect(['index']);
            }

        }

        return $this->render('create', ['model' => $model, 'itemModels' => $itemModels, 'count' => 0]);
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
    }


    /**
     * Updates an existing Proposal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Proposal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Proposal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Proposal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Proposal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
