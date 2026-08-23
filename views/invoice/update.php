<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */

$this->title = 'Редагування: ' . $invoiceModel->invoice;
$this->params['breadcrumbs'][] = ['label' => 'Документи', 'url' => ['index', 'action' => $invoiceModel->document_type]];
$this->params['breadcrumbs'][] = ['label' => $invoiceModel->invoice, 'url' => ['view', 'id' => $invoiceModel->id]];
$this->params['breadcrumbs'][] = 'Редагування';
?>

<?php if ($invoiceModel->document_type == 'import'): ?>
    <?= $this->render('_form_import', [
        'invoiceModel'  => $invoiceModel,
        'itemModels'    => $itemModels,
        'paymentModels' => $paymentModels,
        'count'         => $count,
    ]) ?>
<?php elseif ($invoiceModel->document_type == 'order'): ?>
    <?= $this->render('_order_form', [
        'invoiceModel'  => $invoiceModel,
        'itemModels'    => $itemModels,
        'paymentModels' => $paymentModels,
        'count'         => $count,
    ]) ?>
<?php else: ?>
    <?= $this->render('_form', [
        'invoiceModel'  => $invoiceModel,
        'itemModels'    => $itemModels,
        'paymentModels' => $paymentModels,
        'count'         => $count,
    ]) ?>
<?php endif; ?>
