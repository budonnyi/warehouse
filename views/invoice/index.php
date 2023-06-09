<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый документ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $saleTotal = 0;
    foreach ($dataProvider->models as $item) {
        $status = $item->transfer_type == 'income' ? -1 : ($item->transfer_type == 'sale' ? 1 : 0);
        $saleTotal += (empty($item->price)) ? 0 : $item->price * $item->quantity * $status;
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
//        'footer' => $saleTotal,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

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
                'attribute' => 'product_id',
                'label' => 'Продукция',
                'filter' => \yii\helpers\ArrayHelper::map(app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                'headerOptions' => ['style' => 'text-align:center'],
                'footer' => '<b>Итого :</b>',
                'value' => function ($data) {
                    return $data->products->name;
                }
            ],
            [
                'attribute' => 'customer_id',
                'label' => 'Клиент',
                'filter' => \yii\helpers\ArrayHelper::map(app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                'headerOptions' => ['style' => 'text-align:center'],
                'value' => function ($data) {
                    return $data->customers->name;
                }
            ],
            'quantity',
            [
                'attribute'         => 'price',
                'contentOptions'    => ['class' => 'text-right',],
                'footerOptions'     => ['class' => 'text-right'],
                'value'             => 'price',
            ],
            [
                'label'             => 'Стоимость',
                'contentOptions'    => ['class' => 'text-right',],
                'headerOptions'     => ['style' => 'text-align:center; width: 8%'],
                'format'            => 'currency',
                'footer'            => '<b>' . Yii::$app->formatter->asCurrency($saleTotal) . '</b>',
                'footerOptions'     => ['class' => 'text-right'],
                'value'             => function ($data) {
//                    return $data->price * $data->quantity;
                }
            ],
            [
                'attribute'         => 'transfer_type',
                'label'             => 'Действие',
                'filter'            => array("income" => "Поступление", "sale" => "Реализация", 'transfer' => 'Трансфер'),
                'headerOptions'     => ['style' => 'text-align:center'],
                'value'             => function ($data) {
                    return $data->transfer_type == 'income' ? 'Поступление' : ($data->transfer_type == 'sale' ? 'Реализация' : 'Трансфер');
                },
            ],
            [
                'attribute'         => 'date',
                'label'             => 'Дата',
                'filter'            => \yii\helpers\ArrayHelper::map(app\models\Invoice::find()->orderBy(['date' => SORT_DESC])->all(), 'date', 'date'),
                'headerOptions'     => ['style' => 'text-align:center'],
                'value'             => function ($data) {
                    return date('d-m-Y', strtotime($data->date));
                },
            ],
            [
                'attribute'         => 'store',
                'label'             => 'Склад',
                'filter'            => array("main" => "Основной", "additional" => "Дополнительный"),
                'headerOptions'     => ['style' => 'text-align:center'],
                'value'             => function ($data) {
                    return $data->store == 'main' ? 'Основной' : 'Дополнительный';
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
