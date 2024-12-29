<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Payment $model */

$this->title = 'Редагувати платіж: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Платежі', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагувати';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'invoices' => $invoices
]) ?>
