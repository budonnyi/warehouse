<?php

use app\models\Invoice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\InvoiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документи';
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
                        <?php $documentTypes = ['bill' => 'Новий рахунок', 'sale' => 'Нова накладна', 'order' => 'Додати до замовленя', 'import' => 'Новий інвойс', 'income' => 'Нова закупка', 'office' => 'Офісні', 'rejected' => 'Відмінені'] ?>
                        <?= Html::a($documentTypes[$action], ['create', 'action' => $action], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="direct-chat-messages" style="height: 100%;">

                    <?php Pjax::begin(); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'rowOptions' => function ($model) {
                            if ($model->status == 1) {
                                return ['style' => 'background-color: #d9ead3'];
                            } elseif (in_array($model->status, [2, 6, 7, 9])) {
                                return ['style' => 'background-color: #fff2cc'];
                            } elseif (in_array($model->status, [3, 4, 5])) {
                                return ['style' => 'background-color: #d1e2f2'];
                            } elseif ($model->status == 0) {
                                return ['style' => 'background-color: #fdd7e4'];
                            }
                        },
                        'columns' => [
                            [
                                'class' => 'yii\grid\CheckboxColumn', // Додаємо колонку з чекбоксами
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '5%'],
                                'checkboxOptions' => function ($model) {
                                    return ['value' => $model->id]; // Значення чекбокса буде ID моделі
                                },
                            ],
                            [
                                'attribute' => 'order_num',
                                'visible' => $action == 'import',
                                'label' => 'Order',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return (!empty($data->order_date) ? date('d.m.Y', strtotime($data->order_date)) : '') . '<br>' . '<a href="' . Url::to(['view', 'id' => $data->id]) . '">' . $data->order_num . '</a>';
                                },
                                'format' => 'html'
                            ],
//                            [
//                                'attribute' => 'date',
//                                'visible' => in_array($action, ['sale', 'income']),
//                                'label' => 'Дата накладної',
//                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
//                                'value' => function ($data) {
//                                    return !empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '';
//                                }
//                            ],
                            [
                                'attribute' => 'bill',
                                'label' => 'Рахунок',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'visible' => $action != 'import', // || $action != 'bill',
                                'value' => function ($data) {
                                    return (!empty($data->bill_date) ? date('d.m.Y', strtotime($data->bill_date)) : '') . '<br>' . '<a href="' . Url::to(['view', 'id' => $data->id]) . '">' . $data->bill . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'invoice',
                                'label' => 'Накладна',
                                'visible' => $action != 'import', // || $action != 'bill',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return (!empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '') . '<br>' . '<a href="' . Url::to(['view', 'id' => $data->id]) . '">' . $data->invoice . '</a>';
                                },
                                'format' => 'html'
                            ],

                            [
                                'attribute' => 'bill',
                                'label' => 'Adv invoice',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'visible' => $action == 'import',
                                'value' => function ($data) {
                                    return (!empty($data->bill_date) ? date('d.m.Y', strtotime($data->bill_date)) : '') . '<br>' . '<a href="' . Url::to(['view', 'id' => $data->id]) . '">' . $data->bill . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'invoice',
                                'visible' => $action == 'import',
                                'label' => 'Invoice',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return (!empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '') . '<br>' . '<a href="' . Url::to(['view', 'id' => $data->id]) . '">' . $data->invoice . '</a>';
                                    },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'customer_id',
                                'label' => 'Клиент',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '25%'],
                                'filter' => \yii\helpers\ArrayHelper::map(app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                'visible' => $action != 'import',
                                'value' => function ($data) {
                                    return '<a href="' . Url::to(['customer/view', 'id' => $data->customer_id]) . '">' . ($data->customers->name ?? '-') . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'label' => 'Продукція',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '35%'],
                                'value' => function ($data) {
                                    $html = '<table class=" table-borderless" style="width: 100%;font-size: 14px">';
                                    foreach ($data->items as $item) {
                                        $html .= !in_array($item->products->name, ['EMB Packing', 'Ex-1 document fee']) ? '<tr><td style="line-height: 15px;padding: 0 5px;margin: 0;width: 70%; text-align: left">'
                                            . ($item->products->name ?? '-')
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 10%; text-align: center">' . $item->quantity
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 20%; text-align: right">' . $item->price . '</td></tr>' : '';
                                    }
                                    $html .= '</table>';
                                    return $html;
                                },
                                'format' => 'html'
                            ],
                            [
                                'label' => 'Оплачено',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '15%'],
                                'visible' => $action != 'import',
                                'value' => function ($data) {
                                    $html = '<table class=" table-borderless" style="width: 100%;font-size: 12px">';
                                    $payment['income'] = $payment['payment'] = 0;
                                    foreach ($data->payments as $item) {
                                        $payment[$item->direction] += $item->amount;
                                    }
                                    if ($payment['income'] > 0)
                                        $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 40%">'
                                            . 'Получено'
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 40%;color:green; text-align: right">' . number_format($payment['income'], 2, '.', "'") . '</td></tr>';
                                    if ($payment['payment'] > 0)
                                        $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;">'
                                            . 'Сплачено'
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;color:red; text-align: right">' . '-' . number_format($payment['payment'], 2, '.', "'") . '</td></tr>';
                                    if ($payment['income'] > 0 && $payment['payment'] > 0)
                                        $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;font-weight: 700">'
                                            . 'Різниця'
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;color:green; text-align: right">' . number_format($payment['income'] - $payment['payment'], 2, '.', "'") . '</td></tr>';
                                    $html .= '</table>';
                                    return $html;
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'total_amount',
                                'label' => 'Сума',
                                'visible' => $action != 'import',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return number_format($data->total_amount, 2, '.', ' ');
                                }
                            ],
                            [
                                'attribute' => 'total_amount_sek',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'visible' => $action == 'import'
                            ],
                            [
                                'attribute' => 'status',
                                'label' => 'Статус',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'filter' => Yii::$app->params['statuses'],
                                'value' => function ($data) {
                                    $statuses = Yii::$app->params['statuses'];
                                    return $statuses[$data->status];
                                },
                            ],
                            [
                                'label' => 'Файли',
                                'visible' => $action == 'import',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return !empty($data->attachments) ? count($data->attachments) : '';
                                }
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'urlCreator' => function ($action, Invoice $model, $key, $index, $column) {
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
