<?php

use app\models\Invoice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\InvoiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
//                    ['class' => 'yii\grid\SerialColumn'],

//                    'id',
                    'invoice',
                    'date',
                    'bill',
                    'bill_date',
                    //'contract',
                    //'contract_date',
                    //'customer_id',
                    //'quantity',
                    'total_amount',
                    //'total_amount_sek',
                    'document_type',
                    //'store',
                    //'comment',
                    //'sek_rate',
                    //'custom_taxes',
                    //'transport_fee',
                    //'brocker_fee',
                    //'additional_cost',
                    //'status',
                    //'checked',
                    //'documents',
                    //'created_at',
                    //'updated_at',
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Invoice $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>
    </section>
</div>

