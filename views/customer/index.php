<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый клиент', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => 'Клиент',
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\Customer::find()->orderBy(['created_at' => SORT_DESC])->all(), 'name', 'name'),
                'headerOptions' => ['style' => 'text-align:center'],
                'value' => function ($data) {
                    return $data->name;
                },
            ],
            'email:email',
            [
                'attribute' => 'phone',
                'label' => 'Телефон',
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\Customer::find()->orderBy(['created_at' => SORT_DESC])->all(), 'phone', 'phone'),
                'headerOptions' => ['style' => 'text-align:center'],
                'value' => function ($data) {
                    return $data->phone;
                },
            ],
            'address:ntext',
            [
                'attribute' => 'type',
                'filter' => array('customer' => 'Покупатель', 'supplier' => 'Поставщик'),
                'value' => function ($data) {
                    return $data->type == 'customer' ? 'Покупатель' : 'Поставщик';
                }
            ],
//            'type',
            //'comment:ntext',
            //'status',
//            [
//                'attribute' => 'created_at',
//                'value' => function ($data) {
//                    return date('d.m.Y', $data->created_at);
//                }
//            ],
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
