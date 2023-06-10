<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Склад';
$this->params['breadcrumbs'][] = $this->title;

$startDate = date('Y-m-d', strtotime(Yii::$app->session->get('start-date')));
$endDate = date('Y-m-d', strtotime(Yii::$app->session->get('end-date')));
$selectedDate = $startDate . ' - ' . $endDate;

//var_dump($selectedDate); die;

?>

<div class="container-flex" style="margin: 0px 50px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/partials/filter-data', [
        'selectedDate' => $selectedDate,
        'modelSelect' => $modelSelect
    ]) ?>

    <table class="table table-striped table-bordered" style="margin-top: 50px">
        <thead>
        <tr style="font-weight: 800">
            <td>Товар</td>
            <td>Куплено</td>
            <td>На сумму</td>
            <td>Сред цена</td>
            <td>Продано</td>
            <td>На сумму</td>
            <td>Количество на складе</td>
            <td>На сумму</td>
            <td>Проффит грн</td>
            <td>Маржинальность</td>
            <td>Склад продажа</td>
        </tr>
        <thead>
        <tbody>

        <?php $storeAmount = 0 ?>
        <?php $incomeAmount = 0 ?>
        <?php $saleAmount = 0 ?>
        <?php $proffitAmount = 0 ?>
        <?php $total = 0 ?>

        <tr style="font-weight: 800; background-color: #ccc">
            <td>ИТОГО</td>
            <td></td>
            <td class="text-right"><?= number_format($amount['income'], 2, ',', ' '); ?></td>
            <td></td>
            <td></td>
            <td class="text-right"><?= number_format($amount['sale'], 2, ',', ' '); ?></td>
            <td></td>
            <td class="text-right"><?= number_format($amount['store'], 2, ',', ' '); ?></td>
            <td class="text-right"><?= number_format($amount['proffit'], 2, ',', ' '); ?></td>
            <td></td>
            <td></td>
        </tr>

        <?php foreach ($store as $product_id => $item) { ?>
            <?php if ($item['store_quantity'] != 0) { ?>
                <tr>
                    <!-- Товар-->
                    <td><?= $item['product'] ?></td>
                    <!-- Куплено-->
                    <td class="text-center"><?= $item['income_quantity'] ?></td>
                    <!-- На сумму-->
                    <td class="text-right"><?= $item['income_amount'] ?></td>
                    <!-- Сред цена-->
                    <td class="text-right"><?= $item['averige_price'] ?></td>
                    <!-- Продано-->
                    <td class="text-center"><?= $item['sale_quantity'] ?></td>
                    <!-- На сумму-->
                    <td class="text-right"><?= $item['sale_amount'] ?></td>
                    <!-- Количество на складе-->
                    <td class="text-center"><?= $item['store_quantity'] ?></td>
                    <!-- На сумму-->
                    <td class="text-right"><?= $item['store_amount'] ?></td>
                    <!-- Проффит грн-->
                    <td class="text-right"><?= $item['proffit'] ?></td>
                    <td class="text-center">
                        <?= floatval($item['sale_quantity']) > 0 ? round(floatval(str_replace(' ', '', $item['proffit'])) / floatval($item['sale_quantity']), 2) : '-' ?>
                    </td>
                    <?php $pri = floatval($item['sale_quantity']) > 0 ? round(floatval(str_replace(' ', '', $item['proffit'])) / floatval($item['sale_quantity']), 2) : 0 ?>
                    <td class="text-right" style="color: red; font-weight: 600">
                        <?= number_format(round((floatval($pri) + floatval(str_replace(' ', '',$item['averige_price']))) * floatval($item['store_quantity']) , 2), 2, ',', ' ') ?></td>
                    <?php $total += round((floatval($pri) + floatval(str_replace(' ', '',$item['averige_price']))) * floatval($item['store_quantity']), 2) ?>
                </tr>
            <?php } ?>
        <?php } ?>

        <tr style="font-weight: 800; background-color: #ccc">
            <td>ИТОГО</td>
            <td></td>
            <td class="text-right"><?= number_format($amount['income'], 2, ',', ' '); ?></td>
            <td></td>
            <td></td>
            <td class="text-right"><?= number_format($amount['sale'], 2, ',', ' '); ?></td>
            <td></td>
            <td class="text-right"><?= number_format($amount['store'], 2, ',', ' '); ?></td>
            <td class="text-right"><?= number_format($amount['proffit'], 2, ',', ' '); ?></td>
            <td></td>
            <td class="text-right" style="color: green"><?= number_format($total, 2, ',', ' ') ?></td>
        </tr>
        </tbody>
    </table>


</div>
