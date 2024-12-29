<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Payment $model */

$this->title = 'Новий платіж';
$this->params['breadcrumbs'][] = ['label' => 'Платежі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'invoices' => $invoices
]) ?>