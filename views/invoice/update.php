<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */

$this->title = 'Update Invoice: ' . $invoiceModel->invoice;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $invoiceModel->invoice, 'url' => ['view', 'id' => $invoiceModel->invoice]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'invoiceModel' => $invoiceModel,
                'itemModels' => $itemModels,
                'count' => $count
            ]) ?>
        </div>
    </section>
</div>

