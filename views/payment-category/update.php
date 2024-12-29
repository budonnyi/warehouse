<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PaymentCategory $model */

$this->title = 'Редагування категорії платежу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Payment Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
