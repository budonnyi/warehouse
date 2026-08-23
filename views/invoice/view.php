<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var app\models\Invoice $model */

$this->title = $model->invoice ?? $model->bill;
$this->params['breadcrumbs'][] = ['label' => 'Накладні', 'url' => ['index', 'action' => $model->document_type]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="card">
<!--    <div class="card-header">-->
<!--        <h2 class="card-title">--><?php //= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?><!--</h2>-->
<!--    </div>-->
    <div class="card-header">
        <div class="row">
            <div class="col-md-3 col-6">
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
            <div class="col-md-3 col-6">
                <?php if (!empty($model->bill_date) && !empty($model->bill) && !empty($model->total_amount) && $model->document_type !== 'import') { ?>
                    <?= Html::a('Рахунок PDF', ['bill-pdf', 'id' => $model->id], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                <?php } ?>
            </div>
            <div class="col-md-3 col-6">
                <?php if (!empty($model->date) && !empty($model->invoice) && !empty($model->total_amount) && $model->document_type !== 'import') { ?>
                    <?= Html::a('Накладна PDF', ['invoice-pdf', 'id' => $model->id], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered'],
//                'tableOptions' => ['class' => 'table table-bordered'],
                'attributes' => [
//                    [
//                        'attribute' => 'invoice',
//                        'headerOptions' => ['style' => 'text-align:center', 'width' => '20%'],
//                        'value' => function ($data) {
//                            $invoice = '№' . $data->invoice ?? '';
//                            $date = !empty($data->date) ? ' від ' . date('d.m.Y', strtotime($data->date)) : '';
//                            return $invoice . $date;
//                        }
//                    ],
                    [
                        'attribute' => 'bill',
                        'headerOptions' => ['style' => 'text-align:center', 'width' => '20%'],
                        'value' => function ($data) {
                            $invoice = '№' . $data->bill ?? '';
                            $date = !empty($data->bill_date) ? ' від ' . date('d.m.Y', strtotime($data->bill_date)) : '';
                            return $invoice . $date;
                        }
                    ],
                    [
                        'attribute' => 'customer_id',
                        'label' => 'Кліент',
                        'headerOptions' => ['style' => 'text-align:center', 'width' => '20%'],
                        'filter' => \yii\helpers\ArrayHelper::map(app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        'value' => function ($data) {
                            $html = '<a href="' . \yii\helpers\Url::to(['customer/view', 'id' => $data->customers->id ?? '']) . '">'
                                . ($data->customers->name ?? '-') . '</a>';
                            return $html;
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'total_amount',
                        'value' => function ($data) {
                            return number_format($data->total_amount, 2, '.', '\'');
                        },
                        'format' => 'html'
                    ],
                    'comment',
                ],
            ]) ?>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Документи') ?></h2>
        </div>
        <div class="card-header">
<!--        <div class="direct-chat-messages" style="height: 100%;">-->
            <?php $form = \yii\widgets\ActiveForm::begin(); ?>
            <?php if (!empty($model->attachments) && !empty($model->id)): ?>
                <?php
                $img = [];
                $json = [];
                foreach ($model->attachments as $attachment) {
                    $root = '/files/' . $model->id . '/';
                    $img[] = $root . $attachment->filename;

                    $type = pathinfo($attachment->filename, PATHINFO_EXTENSION);
                    $json[] = [
                        'type' => in_array($type, ['jpg', 'jpeg']) ? 'image' : $type,
                        'caption' => $attachment->filename,
                        \yii\helpers\Url::to(['/attachment/delete-upload']),
                        'key' => 'filename ' . $attachment->id,
                    ];
                }
                ?>

                <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
                    'options' => ['accept' => '', 'multiple' => true],
                    'pluginOptions' => [
//                'showRemove' => false,
                        'showDownload' => true,
//                'showUpload' => false,
                        'initialPreviewAsData' => true,
//            'initialCaption' => "The Moon and the Earth",
                        'showCancel' => false,
                        'showPreview' => true,
//            'showCaption'          => false,
                        'initialPreview' => $img,
                        'initialPreviewConfig' => $json,
                        'previewSettings' => [
//                'pdf' => ['width' => "auto", 'height' => "auto", 'max-width' => "100%", 'max-height' => "100%"],
                            'image' => ['width' => "auto", 'height' => "auto", 'max-width' => "100%", 'max-height' => "100%"],
                        ],
                        'previewFileType' => 'any',
                        'uploadAsync' => true,
                        'deleteUrl' => \yii\helpers\Url::to(['/attachment/delete-upload']),
                        'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
                        'uploadExtraData' => [
                            'entity_id' => $model->id,
                            'entity_type' => \app\models\Attachment::INVOICE
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
                            'entity_type' => \app\models\Attachment::INVOICE
                        ],
                    ],
                ]); ?>
            <?php endif; ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Товари') ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $productDataProvider,
                'tableOptions' => ['class' => 'table table-bordered'],
                'columns' => [
                    [
                        'attribute' => 'product_id',
                        'label' => 'Товар',
                        'headerOptions' => ['style' => 'text-align:center'],
                        'value' => function ($data) {
                            return '<a href="' . \yii\helpers\Url::to(['product/view', 'id' => $data->product_id]) . '">' . ($data->products->name ?? '-') . '</a>';
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'quantity',
                        'label' => 'Кількість',
                        'headerOptions' => ['style' => 'text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'value' => function ($data) {
                            return $data->quantity;
                        },
                    ],
                    [
                        'attribute' => 'price',
                        'label' => 'Ціна',
                        'headerOptions' => ['style' => 'text-align:center'],
                        'contentOptions' => ['style' => 'text-align:right'],
                        'value' => function ($data) {
                            return number_format($data->price, 2, '.', '\'');
                        },
                        'format' => 'html'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Оплати') ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $paymentDataProvider,
//                'showHeader' => false,
//            'showFooter' => true,
                'tableOptions' => ['class' => 'table table-bordered'],
                'columns' => [
                    [
                        'attribute' => 'date',
                        'headerOptions' => ['style' => 'text-align:center'],
                        'value' => function ($data) {
                            return !empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '';
                        }
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
                        'contentOptions' => ['style' => 'text-align:right'],
                        'value' => function ($data) {
                            $color = $data->direction == 'payment' ? 'red' : 'green';
                            $sign = $data->direction == 'payment' ? (-1) : 1;
                            return '<p style="color: ' . $color . '">' . number_format($data->amount * $sign, 2, '.', '\'') . '<p>';
                        },
                        'format' => 'html'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<div class="row"></div>