<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукти', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="card">
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
                'attributes' => [
//                    'id',
                    'name',
                    'articul',
                    [
                        'attribute' => 'category_id',
                        'value' => function ($data) {
                            return $data->category->title ?? '-';
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($data) {
                            return $data->status ? 'Активний' : 'Прихований';
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="card-header">
            <h2 class="card-title"><?= Yii::t('app', 'Історія поставок') ?></h2>
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th style="text-align:center">Дата</th>
                    <th style="text-align:center">Документ</th>
                    <th style="text-align:center">Контрагент</th>
                    <th style="text-align:center">Тип</th>
                    <th style="text-align:center">Надходження</th>
                    <th style="text-align:center">Реалізація</th>
                    <th style="text-align:center">Ціна</th>
                    <th style="text-align:center">Разом</th>
                    <th style="text-align:center">Кількість</th>
                </tr>

                </thead>
                <tbody>
                <?php $totalAmount = 0; ?>
                <?php foreach ($models as $item) { ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($item->invoices->document_type == 'bill' ? $item->invoices->bill_date : $item->invoices->date)) ?? '---' ?></td>
                        <td>
                            <a href="<?= \yii\helpers\Url::to(['invoice/view', 'id' => $item->invoice_id]) ?>">
                                <?= $item->invoices->document_type == 'bill' ? $item->invoices->bill : $item->invoices->invoice ?></a>
                        </td>
                        <td>
                            <a href="<?= \yii\helpers\Url::to(['customer/view', 'id' => $item->invoices->customers->id ?? '']) ?>">
                                <?= $item->invoices->customers->name ?? '---' ?>
                        </td>
                        <td><?= Yii::$app->params['document_types'][$item->invoices->document_type] ?? '---' ?></td>
                        <td><?= in_array($item->invoices->document_type, ['income', 'import']) ? $item->quantity : '' ?></td>
                        <td><?= !in_array($item->invoices->document_type, ['income', 'import']) ? $item->quantity : '' ?></td>
                        <td><?= $item->price ?></td>
                        <td><?= $item->quantity * $item->price ?></td>
                        <?php if (!empty($item->invoices->date)) { ?>
                            <?php switch ($item->invoices->document_type) {
                                case 'income':
                                case 'import':
                                    $totalAmount += $item->quantity;
                                    break;
                                case 'sale':
                                    $totalAmount -= $item->quantity;
                                    break;
                            } ?>
                        <?php } ?>
                        <td><?= $totalAmount ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>