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

$this->title = 'Склад';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
<h1><?= Html::encode($this->title) ?></h1>
            </div>

            <div class="card-body p-0">
                <div class="direct-chat-messages" style="height: 100%;">
<table class="table table-striped">
    <thead>
    <tr>
        <th><?= Yii::t('app', 'ID') ?></th>
        <th><?= Yii::t('app', 'Товар') ?></th>
        <th><?= Yii::t('app', 'На складі') ?></th>
        <th><?= Yii::t('app', 'Замовлено') ?></th>
        <th><?= Yii::t('app', 'Оплачено') ?></th>
        <th><?= Yii::t('app', 'Рахунки') ?></th>
        <th><?= Yii::t('app', 'Продали') ?></th>
        <th><?= Yii::t('app', 'Купили') ?></th>
        <th><?= Yii::t('app', 'Прибуток') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($storeItems != 0) { ?>
        <?php foreach ($storeItems as $productId => $storeItem) { ?>
            <?php if (!empty($storeItem['onStoreQuantity']) || !empty($storeItem['orderedQuantity'])) { ?>
                <tr>
                    <td><?= $productId ?></td>
                    <td><a href="<?= Url::toRoute(['product/view', 'id' => $productId]) ?>"><?= $storeItem['product_name'] ?? '' ?></a></td>
                    <td><?= $storeItem['onStoreQuantity'] ?? '' ?></td>
                    <td><?= $storeItem['orderedQuantity'] ?? '' ?></td>
                    <td><?= $storeItem['payed'] ?? '' ?></td>
                    <td><?= $storeItem['billedQuantity'] ?? '' ?></td>
                    <td><?= $storeItem['sold'] ?? '' ?></td>
                    <td><?= $storeItem['income'] ?? '' ?></td>
                    <td><?= $storeItem['profit'] ?? '' ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
                </div>
            </div>
        </div>
    </section>
</div>