<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CustomersStaff $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Customers Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customers-staff-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'customer_id',
                'label' => 'Кліент',
                'headerOptions' => ['style' => 'text-align:center', 'width' => '20%'],
                'value' => function ($data) {
                    $customers = \yii\helpers\ArrayHelper::map(app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
                    $html = '<a href="' . \yii\helpers\Url::to(['customer/view', 'id' => $data->customer_id ?? '']) . '">'
                        . ($customers[$data->customer_id] ?? '-') . '</a>';
                    return $html;
                },
                'format' => 'html'
            ],
            'email:email',
            'phone',
            'comment:ntext',
            'status',
            'position',
        ],
    ]) ?>

</div>
