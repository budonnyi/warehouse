<?php

use app\models\Payment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\PaymentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Платежі';
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
                        <?= Html::a('Новий платіж', ['create'], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
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
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
                            [
                                'attribute' => 'direction',
                                'label' => 'Напрямок',
                                'filter' => ['payment' => 'Оплата', 'income' => 'Надходженя'],
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    $direction = ['payment' => 'Оплата', 'income' => 'Надходженя'];
                                    return $direction[$data->direction] ?? '-';
                                },
                            ],
                            'date',
                            'amount',
                            [
                                'attribute' => 'currency',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    return $data->currency == 'uah' ? 'ГРН' : 'SEK';
                                }
                            ],
                            [
                                'attribute' => 'invoice_id',
                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Invoice::find()->orderBy(['invoice' => SORT_ASC])->all(), 'id', 'invoice'),
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    $link = !empty($data->invoice->id) ? '<a href="' . Url::toRoute(['invoice/view', 'id' => $data->invoice->id]) . '">' . $data->invoice->invoice . '</a>' : '-';
                                    return $link;
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'customer_id',
                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    return $data->customer->name ?? '-';
                                },
                            ],
                            [
                                'attribute' => 'category_id',
                                'label' => 'Категорія',
                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->orderBy(['title' => SORT_DESC])->all(), 'id', 'title'),
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    return $data->category->title ?? '-';
                                },
                            ],
                            //'status',
                            [
                                'class' => ActionColumn::className(),
                                'urlCreator' => function ($action, Payment $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </section>
</div>
