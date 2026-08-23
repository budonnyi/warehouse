<?php

use app\models\CustomersStaff;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Customers Staff';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-staff-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Customers Staff', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
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
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return $data->status ? 'Активний' : 'Пасивний';
                },
            ],
            //'position',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CustomersStaff $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
