<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use app\models\Invoice;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/** @var yii\web\View $this */
$this->registerJsFile('/dist/js/pages/dashboard.js', ['position' => \yii\web\View::POS_END]);

$this->title = 'Warehouse Application';
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row mb-2 pt-3">
    <div class="col-md-5 col-xs-12">
        <h1 class="m-0">Статистика</h1>
    </div>
    <div class="col-md-3 col-xs-12">
        <?php echo DatePicker::widget([
            'name' => 'main_date_from',
            'language' => 'uk',
            'value' => $filter['dateFrom'] ?? date('Y-m-d', time()),
            'options' => ['placeholder' => 'Обери дату ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>
    <div class="col-md-3 col-xs-12">
        <?php echo DatePicker::widget([
            'name' => 'main_date_to',
            'language' => 'uk',
            'value' => $filter['dateTo'] ?? date('Y-m-d', time()),
            'options' => ['placeholder' => 'Обери дату ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>
    <div class="col-md-1 col-12 col-xs-12">
        <?= Html::submitButton('Підібрати', ['class' => 'btn btn-success']) ?>
    </div>
    <!-- /.col -->
    <!--<div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
        </ol>
    </div>--><!-- /.col -->
</div><!-- /.row -->
<?php ActiveForm::end(); ?>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= number_format($result['summResult'] ?? 0, 2, '.', ' ')  ?></h3>

                <p>Реалізація</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="<?= Url::to(['invoice/index', 'action' => 'sale', 'dates' => $filter['dateFrom'] . ';' . $filter['dateTo']]) ?>" class="small-box-footer">Переглянути <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
<!--    <div class="col-lg-3 col-6">-->
<!--       small box -->
<!--        <div class="small-box bg-success">-->
<!--            <div class="inner">-->
<!--                <h3>--><?php //= $result['import_amount'] ?? 0 ?>
<!--                    <sup style="font-size: 20px">грн</sup></h3>-->
<!---->
<!--                <p>Імпортовано --><?php //= $result['import_deals'] ?? 0 ?><!-- поставок</p>-->
<!--            </div>-->
<!--            <div class="icon">-->
<!--                <i class="ion ion-stats-bars"></i>-->
<!--            </div>-->
<!--            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
<!--        </div>-->
<!--    </div>-->
    <!-- ./col -->
    <style>
        .small-box h3 {
            font-size: 1.4rem !important
        }
        .small-box p {
            font-size: 15px !important
        }
    </style>
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $result['contracts'] ?? 0 ?></h3>

                <p>Контрактів</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?= Url::to(['invoice/index', 'action' => 'sale', 'dates' => $filter['dateFrom'] . ';' . $filter['dateTo']]) ?>" class="small-box-footer">Переглянути <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $result['productsResult'][1] ?? 0 ?></h3>

                <p>Товарів продано</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
<!--    <div class="col-lg-3 col-6">-->
<!--         small box -->
<!--        <div class="small-box bg-danger">-->
<!--            <div class="inner">-->
<!--                <h3>--><?php //= $result['moneyReceived'][1] ?? 0 ?><!--</h3>-->
<!---->
<!--                <p>Отримано грн</p>-->
<!--            </div>-->
<!--            <div class="icon">-->
<!--                <i class="ion ion-pie-graph"></i>-->
<!--            </div>-->
<!--            <a href="--><?php //= Url::to(['payment/index', 'action' => 'sale', 'dates' => $filter['dateFrom'] . ';' . $filter['dateTo']]) ?><!--" class="small-box-footer">Переглянути <i class="fas fa-arrow-circle-right"></i></a>-->
<!--        </div>-->
<!--    </div>-->
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $result['productsResult'][2] ?? 0 ?></h3>

                <p>Замовлено</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row">
    <section class="col-lg-8 connectedSortable">
        <div class="card" style="height: 394px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Остані продажі
                </h3>
            </div>
            <div class="card-body">
                <div class="direct-chat-messages" style="height: 100%;">

                    <?php Pjax::begin(); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'showHeader' => false,
                        'rowOptions' => function ($model) {
                            if ($model->status != 1) {
                                return ['style' => 'background-color: #fdd7e4'];
                            }
                        },
                        'columns' => [
                            [
                                'attribute' => 'invoice',
                                'label' => 'Накладна',
                                'contentOptions' => ['style' => 'width:5%; white-space: normal;'],
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '5%'],
                                'value' => function ($data) {
                                    return '<a href="/invoice/view?id=' . $data->id . '">' . $data->invoice . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'date',
                                'label' => 'Дата накладної',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '6%'],
                                'value' => function ($data) {
                                    return date('d.m.Y', strtotime($data->date));
                                }
                            ],
                            [
                                'attribute' => 'customer_id',
                                'label' => 'Клиент',
                                'contentOptions' => ['style' => 'width:50%; white-space: normal;'],
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return '<a href="/customer/view?id=' . $data->customers->id . '">' . $data->customers->name . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'total_amount',
                                'label' => 'Сума',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return number_format($data->total_amount, 2, '.', ' ');
                                }
                            ],
                            [
                                'label' => 'Оплачено',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    $amount = 0;
                                    foreach ($data->payments as $item) {
                                        $amount += $item->amount;
                                    }
                                    return number_format($amount, 2, '.', ' ');
                                }
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>

    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-4 connectedSortable">

        <!-- solid sales graph -->
        <div class="card bg-gradient-info">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-th mr-1"></i>
                    Sales Graph
                </h3>

                <div class="card-tools">
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas class="chart" id="line-chart"
                        style="min-height: 270px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>

        </div>
        <!-- /.card -->
    </section>
    <!-- right col -->
</div>

<div class="row">
    <section class="col-lg-12 connectedSortable">
        <div class="card" style="height: 394px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Чекають оплати/реалізації
                </h3>
            </div>
            <div class="card-body">
                <div class="direct-chat-messages" style="height: 100%;">

                    <?php Pjax::begin(); ?>

                    <?= GridView::widget([
                        'dataProvider' => $billsProvider,
                        'filterModel' => $searchModel,
                        'showHeader' => false,
                        'rowOptions' => function ($model) {
                            if (in_array($model->status, [2, 6, 7])) {
                                return ['style' => 'background-color: #fff2cc'];
                            } elseif (in_array($model->status, [3, 4, 5])) {
                                return ['style' => 'background-color: #d1e2f2'];
                            } elseif ($model->status == 0) {
                                return ['style' => 'background-color: #fdd7e4'];
                            }
                        },
                        'columns' => [
                            [
                                'attribute' => 'bill',
                                'label' => 'Рахунок',
                                'contentOptions' => ['style' => 'width:5%; white-space: normal;'],
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '5%'],
                                'value' => function ($data) {
                                    return '<a href="/invoice/view?id=' . $data->id . '">' . $data->bill . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'bill_date',
                                'label' => 'Дата рахунку',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '6%'],
                                'value' => function ($data) {
                                    return date('d.m.Y', strtotime($data->bill_date));
                                }
                            ],
                            [
                                'attribute' => 'customer_id',
                                'label' => 'Клиент',
                                'contentOptions' => ['style' => 'width:30%; white-space: normal;'],
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '15%'],
                                'value' => function ($data) {
                                    return '<a href="/customer/view?id=' . $data->customers->id . '">' . $data->customers->name . '</a>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'label' => 'Продукція',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '30%'],
                                'value' => function ($data) {
                                    $html = '<table class=" table-borderless" style="width: 100%;font-size: 12px">';
                                    foreach ($data->items as $item) {
                                        $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 70%">'
                                            . ($item->products->name ?? '-')
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 15%">' . $item->quantity
                                            . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 15%">' . $item->price . '</td></tr>';
                                    }
                                    $html .= '</table>';
                                    return $html;
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'total_amount',
                                'label' => 'Сума',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '10%'],
                                'value' => function ($data) {
                                    return number_format($data->total_amount, 2, '.', ' ');
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'label' => 'Статус',
                                'headerOptions' => ['style' => 'text-align:center', 'width' => '5%'],
                                'filter' => Yii::$app->params['statuses'],
                                'value' => function ($data) {
                                    $statuses = Yii::$app->params['statuses'];
                                    return $statuses[$data->status];
                                },
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- /.row (main row) -->

<!--<script src="/dist/js/pages/dashboard.js"></script>-->

<!--<script src="/dist/js/pages/dashboard.js"></script>-->
<script>

    // $(function () {
    //
    // })
    var salesGraphChartData = {
        labels: JSON.parse('<?= json_encode(array_keys($graph)) ?>'), //'['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', ' Q1', ' Q2'],
        datasets: [
            {
                label: 'Digital Goods',
                fill: false,
                borderWidth: 2,
                lineTension: 0,
                spanGaps: true,
                borderColor: '#efefef',
                pointRadius: 3,
                pointHoverRadius: 7,
                pointColor: '#efefef',
                pointBackgroundColor: '#efefef',
                data: JSON.parse('<?= json_encode(array_values($graph)) ?>'), //[2666, 2778, 4912, 3767, 6810, 5670, 4820, 15073, 10687, 8432]
            }
        ]
    }
</script>