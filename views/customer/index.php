<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контрагенти';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <?= Html::a('Новий контрагент', ['create'], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="direct-chat-messages" style="height: 100%;">

                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
//                            'id',
                            [
                                'attribute' => 'name',
                                'label' => 'Контрагент',
//                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'name', 'name'),
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    return '<a href="' . \yii\helpers\Url::to(['customer/view', 'id' => $data->id]) . '">' . ($data->name ?? '-') . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'phone',
                                'label' => 'Телефон',
//                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['created_at' => SORT_DESC])->all(), 'phone', 'phone'),
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    return $data->phone;
                                },
                            ],
                            [
                                'attribute' => 'type',
                                'filter' => Yii::$app->params['customerTypes'], // array('customer' => 'Покупець', 'supplier' => 'Постачальник', 'common' => 'Загальні'),
                                'value' => function ($data) {
                                    $customerTypes = Yii::$app->params['customerTypes']; //array('customer' => 'Покупець', 'supplier' => 'Постачальник', 'common' => 'Загальні');
                                    return $customerTypes[$data->type] ?? '-';
                                }
                            ],
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </section>
</div>