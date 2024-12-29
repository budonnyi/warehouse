<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */

$this->title = 'Редагування Накладної: ' . $invoiceModel->invoice;
$this->params['breadcrumbs'][] = ['label' => 'Накладні', 'url' => ['index', 'action' => $invoiceModel->document_type]];
$this->params['breadcrumbs'][] = ['label' => $invoiceModel->invoice, 'url' => ['view', 'id' => $invoiceModel->id]];
$this->params['breadcrumbs'][] = 'Редагування';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if ($invoiceModel->document_type == 'import') { ?>
    <?= $this->render('_form_import', [
        'invoiceModel' => $invoiceModel,
        'itemModels' => $itemModels,
        'paymentModels' => $paymentModels,
        'count' => $count,
    ]) ?>
<?php } else { ?>
    <?= $this->render('_form', [
        'invoiceModel' => $invoiceModel,
        'itemModels' => $itemModels,
        'paymentModels' => $paymentModels,
        'count' => $count,
    ]) ?>
<?php } ?>
