<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="card">
    <div class="card-header">
<!--        <h2 class="card-title">--><?php //= Html::encode($this->title) ?><!--</h2>-->
    </div>
    <div class="card-header">
        <div class="row">
            <div class="col-md-3 col-6" style="margin-bottom: 10px">
                <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-block btn-outline-primary btn-sm']) ?>
            </div>
            <div class="col-md-3 col-6">
                <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-block btn-outline-danger btn-sm',
                    'data' => [
                        'confirm' => 'Ви впевнені щодо видалення цього документу?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered'],
                'attributes' => [
//                    'id',
//                    'name',
                    'email:email',
                    'phone',
                    'address:ntext',
                    [
                        'attribute' => 'type',
                        'value' => function ($data) {
                            $typesArr = Yii::$app->params['customerTypes'];
                            return $typesArr[$data->type];
                        },
                    ],

                    'comment:ntext',
                ],
            ]) ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title">Співробітники</h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $staffProvider,
                'columns' => [
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
                ],
            ]); ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Поставки') ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-bordered'],
                'rowOptions' => function ($model) {
                    /*if ($model->status == 1) {
                        return ['style' => 'background-color: #d9ead3'];
                    } else*/
                    if (in_array($model->status, [2, 6, 7, 9])) {
                        return ['style' => 'background-color: #fff2cc'];
                    } elseif (in_array($model->status, [3, 4, 5])) {
                        return ['style' => 'background-color: #d1e2f2'];
                    } elseif ($model->status == 0) {
                        return ['style' => 'background-color: #fdd7e4'];
                    }
                },
                'columns' => [
                    [
                        'attribute' => 'date',
                        'label' => 'Дата накладної',
                        'headerOptions' => ['style' => 'text-align:center', 'width' => '6%'],
                        'value' => function ($data) {
                            return !empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '';
                        }
                    ],
                    [
                        'attribute' => 'invoice',
                        'label' => 'Накладна',
                        'headerOptions' => ['style' => 'text-align:center', 'width' => '15%'],
                        'value' => function ($data) {
                            return '<a href="' . \yii\helpers\Url::to(['invoice/view', 'id' => $data->id]) . '">' . $data->invoice . (!empty($data->bill) ? ('<br>рах № ' . $data->bill) : '') . '</a>';
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
                                    . '<a href="' . \yii\helpers\Url::to(['product/view', 'id' => $item->product_id]) . '">'
                                    . ($item->products->name ?? '-') . '</a>'
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
                        'label' => 'Оплачено',
                        'headerOptions' => ['style' => 'text-align:center', 'width' => '15%'],
                        'value' => function ($data) {
                            $html = '<table class=" table-borderless" style="width: 100%;font-size: 12px">';
                            $payment['income'] = $payment['payment'] = 0;
                            foreach ($data->payments as $item) {
                                $payment[$item->direction] += $item->amount;
                            }
                            if ($payment['income'] > 0)
                                $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%">'
                                    . 'Получено'
                                    . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;color:green; float: right">' . number_format($payment['income'], 2, '.', "'") . '</td></tr>';
                            if ($payment['payment'] > 0)
                                $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;">'
                                    . 'Сплачено'
                                    . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;color:red; float: right">' . '-' . number_format($payment['payment'], 2, '.', "'") . '</td></tr>';
                            if ($payment['income'] > 0 && $payment['payment'] > 0)
                                $html .= '<tr><td style="line-height: 12px;padding: 0 5px;margin: 0;font-weight: 700">'
                                    . 'Різниця'
                                    . '</td><td style="line-height: 12px;padding: 0 5px;margin: 0;width: 50%;color:green; float: right">' . number_format($payment['income'] - $payment['payment'], 2, '.', "'") . '</td></tr>';
                            $html .= '</table>';
                            return $html;
                        },
                        'format' => 'html'
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
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Документи') ?></h2>
        </div>

        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
<?php if (!empty($model->attachments) && !$model->isNewRecord && !empty($model->id)): ?>
    <?php
    $img = [];
    $json = [];
    foreach ($model->attachments as $attachment) {
        $root = '/customers/' . $model->id . '/';
        $img[] = $root . $attachment->filename;

        $type = pathinfo($attachment->filename, PATHINFO_EXTENSION);
        $json[] = [
            'type' => in_array($type, ['jpg', 'jpeg']) ? 'image' : $type,
            'caption' => $attachment->filename,
            'width' => '120px',
            \yii\helpers\Url::to(['/attachment/delete-upload']),
            'key' => 'filename ' . $attachment->id,
        ];
    }
    ?>

    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
        'options' => ['accept' => '', 'multiple' => true],
        'pluginOptions' => [
            'initialPreviewFileType' => 'image',
            'overwriteInitial' => false,
//                'allowedFileExtensions' => ['jpg', 'jpeg'],

            'allowedPreviewTypes' => 'image',
            'showDownload' => true,
            'initialPreviewAsData' => true,
            'showCancel' => false,
            'showPreview' => true,
            'initialPreview' => $img,
            'initialPreviewConfig' => $json,
            'previewSettings' => [
                'image' => ['width' => "auto", 'height' => "auto", 'max-width' => "100%", 'max-height' => "100%"],
            ],
            'previewFileType' => 'any',
            'uploadAsync' => true,
            'deleteUrl' => \yii\helpers\Url::to(['/attachment/delete-upload']),
            'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
            'uploadExtraData' => [
                'entity_id' => $model->id,
                'entity_type' => \app\models\Attachment::CUSTOMER
            ],
        ]
    ])->label(false) ?>
<?php elseif (!empty($model->id)) : ?>
    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::classname(), [
        'options' => ['accept' => '', 'multiple' => true],
        'pluginOptions' => [
            'showCancel' => false,
            'previewFileType' => 'any',
            'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
            'uploadExtraData' => [
                'entity_id' => $model->id,
                'entity_type' => \app\models\Attachment::CUSTOMER
            ],
        ],
    ]); ?>
<?php endif; ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>

<!--<div class="card">-->
<!--    <div class="card-body p-0">-->
<!--        <div class="card-header">-->
<!--            <h2 class="card-title">--><?php //= Yii::t('app', 'Товари') ?><!--</h2>-->
<!--        </div>-->
<!--        <div class="direct-chat-messages" style="height: 100%;">-->
<!--            --><?php //= \yii\grid\GridView::widget([
//                'dataProvider' => $productDataProvider,
//                'tableOptions' => ['class' => 'table table-bordered'],
//                'columns' => [
//                    [
//                        'attribute' => 'items.product_id',
//                        'label' => 'Товар',
//                        'headerOptions' => ['style' => 'text-align:center'],
//                        'value' => function ($data) {
//                            return '<a href="' . \yii\helpers\Url::to(['product/view', 'id' => $data->items->product_id]) . '">' . ($data->items->products->name ?? '-') . '</a>';
//                        },
//                        'format' => 'html'
//                    ],
//                    [
//                        'attribute' => 'items.quantity',
//                        'label' => 'Кількість',
//                        'headerOptions' => ['style' => 'text-align:center'],
//                        'contentOptions' => ['style' => 'text-align:center'],
//                        'value' => function ($data) {
//                            return $data->items->quantity;
//                        },
//                    ],
//                    [
//                        'attribute' => 'price',
//                        'label' => 'Ціна',
//                        'headerOptions' => ['style' => 'text-align:center'],
//                        'contentOptions' => ['style' => 'text-align:right'],
//                        'value' => function ($data) {
//                            return number_format($data->price, 2, '.', '\'');
//                        },
//                        'format' => 'html'
//                    ],
//                ],
//            ]); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Оплати') ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $paymentDataProvider,
//                'showHeader' => false,
                'showFooter' => true,
                'tableOptions' => ['class' => 'table table-bordered'],
                'columns' => [
                    [
                        'attribute' => 'date',
                        'headerOptions' => ['style' => 'text-align:center'],
                        'footerOptions' => ['style' => 'text-align:left; font-weight:700'],
                        'value' => function ($data) {
                            return !empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '';
                        },
                        'footer' => 'Разом:'
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
                        'attribute' => 'amount',
                        'headerOptions' => ['style' => 'text-align:center'],
                        'footerOptions' => ['style' => 'text-align:right; font-weight:700'],
                        'contentOptions' => ['style' => 'text-align:right'],
                        'value' => function ($data) {
                            $color = $data->direction == 'payment' ? 'red' : 'green';
                            $sign = $data->direction == 'payment' ? (-1) : 1;
                            return '<p style="color: ' . $color . '">' . number_format($data->amount * $sign, 2, '.', '\'') . '<p>';
                        },
                        'footer' =>  number_format(\app\models\Payment::getTotal($paymentDataProvider->models, 'amount'), 2, '.', '\''),
                        'format' => 'html'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<div class="row"></div>