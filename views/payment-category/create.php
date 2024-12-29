<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PaymentCategory $model */

$this->title = 'Нова категорія платежу';
$this->params['breadcrumbs'][] = ['label' => 'Категорії платежів', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
