<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */

$this->title = 'Create Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if ($invoiceModel->document_type == 'import') { ?>
    <?= $this->render('_form_import', [
        'invoiceModel' => $invoiceModel,
        'itemModels' => $itemModels,
        'count' => $count,
    ]) ?>
<?php } else { ?>
    <?= $this->render('_form', [
        'invoiceModel' => $invoiceModel,
        'itemModels' => $itemModels,
        'count' => $count,
    ]) ?>
<?php } ?>
