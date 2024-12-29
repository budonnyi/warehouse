<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CustomersStaff $model */

$this->title = 'Create Customers Staff';
$this->params['breadcrumbs'][] = ['label' => 'Customers Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-staff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
