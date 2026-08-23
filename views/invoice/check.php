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

<h1><?= Html::encode($this->title) ?></h1>

<table class="table table-striped">
    <thead>
    <tr>
        <th><?= Yii::t('app', 'ID') ?></th>
        <th><?= Yii::t('app', 'Товар') ?></th>
        <th><?= Yii::t('app', 'На складі') ?></th>
        <th><?= Yii::t('app', 'Замовлено') ?></th>
        <th><?= Yii::t('app', 'Продали') ?></th>
        <th><?= Yii::t('app', 'Купили') ?></th>
        <th><?= Yii::t('app', 'Прибуток') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($storeItems)) { ?>
        <?php foreach ($storeItems as $productId => $storeItem) { ?>
            <?php if (!empty($storeItem['onStoreQuantity'])) { ?>
                <tr>
                    <td><?= $productId ?></td>
                    <td><?= $storeItem['product_name'] ?? '' ?></td>
                    <td><?= $storeItem['onStoreQuantity'] ?? '' ?></td>
                    <td><?= $storeItem['orderedQuantity'] ?? '' ?></td>
                    <td><?= $storeItem['sold'] ?? '' ?></td>
                    <td><?= $storeItem['income'] ?? '' ?></td>
                    <td><?= $storeItem['profit'] ?? '' ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
