<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                        </div>
                        <p>
                            <?= Html::a('Новый документ', ['create'], ['class' => 'btn btn-success']) ?>
                        </p>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <?php
                            $saleTotal = 0;
                            foreach ($dataProvider->models as $item) {
                                $status = $item->document_type == 'income' ? -1 : ($item->document_type == 'sale' ? 1 : 0);
                                $saleTotal += (empty($item->price)) ? 0 : $item->price * $item->quantity * $status;
                            }
                            ?>

                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'tableOptions' => [
                                    'id' => 'theDatatable',
                                    'class' => 'table table-bordered'
                                ],
                                'showFooter' => true,
                                'columns' => [
                                    [
                                        'attribute' => 'invoice',
                                        'label' => 'Документ',
                                        'filter' => \yii\helpers\ArrayHelper::map(app\models\Invoice::find()->orderBy(['date' => SORT_DESC])->all(), 'invoice', 'invoice'),
                                        'headerOptions' => ['style' => 'text-align:center'],
                                        'value' => function ($data) {
                                            return $data->invoice;
                                        },
                                    ],
                                    [
                                        'attribute' => 'date',
                                        'label' => 'Дата',
                                        'filter' => \yii\helpers\ArrayHelper::map(app\models\Invoice::find()->orderBy(['date' => SORT_DESC])->all(), 'date', 'date'),
                                        'headerOptions' => ['style' => 'text-align:center'],
                                        'value' => function ($data) {
                                            return date('d-m-Y', strtotime($data->date));
                                        },
                                    ],
                                    [
                                        'label' => 'Продукция',
                                        'headerOptions' => ['style' => 'text-align:center'],
                                        'value' => function ($data) {
                                            $html = '<span style="font-size: 11px">';
                                            foreach ($data->items as $item) {
                                                $html .= $item->products->name . ' - ' . $item->quantity . ' - ' . $item->price . '<br>';
                                            }
                                            $html .= '</span>';
                                            return $html;
                                        },
                                        'format' => 'html'
                                    ],
                                    [
                                        'attribute' => 'customer_id',
                                        'label' => 'Клиент',
                                        'filter' => \yii\helpers\ArrayHelper::map(app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                        'headerOptions' => ['style' => 'text-align:center'],
                                        'value' => function ($data) {
                                            return $data->customers->name ?? '-';
                                        }
                                    ],
                                    'quantity',
                                    [
                                        'label' => 'Стоимость',
                                        'attribute' => 'total_amount',
                                        'contentOptions' => ['class' => 'text-right',],
                                        'footerOptions' => ['class' => 'text-right'],
                                        'value' => 'total_amount',
                                        'format' => 'currency',
                                    ],

                                    ['class' => 'yii\grid\ActionColumn'],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
