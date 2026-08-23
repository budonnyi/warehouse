<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

yii\bootstrap4\BootstrapAsset::register($this);
yii\bootstrap4\BootstrapPluginAsset::register($this);
yii\web\YiiAsset::register($this);

/** @var yii\web\View $this */
/** @var app\models\Invoice $invoiceModel */
/** @var yii\widgets\ActiveForm $form */

$action = $action ?? $invoiceModel->document_type;

$this->registerCss('
.ef-wrap{
    --c-ink:    #0f172a;
    --c-ink2:   #475569;
    --c-ink3:   #94a3b8;
    --c-border: #e2e8f0;
    --c-bg:     #f8fafc;
    --c-white:  #ffffff;
    --c-green:  #16a34a;
    --c-blue:   #2563eb;
    --c-red:    #dc2626;
    --r: 4px;
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
    color:var(--c-ink);
}
.ef-section{
    background:var(--c-white);border:1px solid var(--c-border);
    border-radius:6px;box-shadow:0 1px 3px rgba(15,23,42,.05);
    margin-bottom:12px;overflow:hidden;
}
.ef-section-head{
    display:flex;align-items:center;justify-content:space-between;
    padding:10px 16px;border-bottom:1px solid var(--c-border);background:var(--c-bg);
}
.ef-section-title{font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--c-ink3);margin:0;}
.ef-section-body{padding:16px;}
.ef-wrap .form-group{margin-bottom:12px;}
.ef-wrap label{font-size:11px;font-weight:600;color:var(--c-ink2);margin-bottom:4px;display:block;letter-spacing:.02em;}
.ef-wrap .form-control{
    font-size:13px;color:var(--c-ink);border:1px solid #cbd5e1;border-radius:var(--r);
    padding:6px 10px;height:34px;transition:border-color .15s,box-shadow .15s;background:#fff;
}
.ef-wrap .form-control:focus{border-color:#60a5fa;box-shadow:0 0 0 2px rgba(96,165,250,.2);outline:none;}
.ef-wrap textarea.form-control{height:auto;}
.ef-wrap .help-block{font-size:11px;color:var(--c-red);margin-top:3px;}
.ef-wrap input[type="date"]{position:relative;}
.ef-wrap input[type="date"]::-webkit-calendar-picker-indicator{
    cursor:pointer;position:absolute;top:0;right:0;bottom:0;left:0;width:auto;
    background-position:right 8px center;background-size:14px;
}
.ef-pair{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.ef-trio{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;}
@media(max-width:576px){.ef-pair,.ef-trio{grid-template-columns:1fr;}}
.ef-customer-row{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:end;}
.ef-customer-row .form-group{margin-bottom:0;}
@media(max-width:576px){.ef-customer-row{grid-template-columns:1fr;}}
.ef-btn{
    display:inline-flex;align-items:center;gap:5px;
    padding:7px 14px;border-radius:var(--r);font-size:12px;font-weight:600;
    border:none;cursor:pointer;text-decoration:none;white-space:nowrap;
    transition:background .15s;line-height:1;height:34px;
}
.ef-btn-primary{background:var(--c-green);color:#fff;}
.ef-btn-primary:hover{background:#15803d;color:#fff;text-decoration:none;}
.ef-btn-info{background:#eff6ff;color:var(--c-blue);border:1px solid #bfdbfe;}
.ef-btn-info:hover{background:#dbeafe;color:#1d4ed8;text-decoration:none;}
.ef-btn-sm{height:26px;padding:4px 10px;font-size:11px;}
.ef-table{width:100%;border-collapse:collapse;font-size:12px;}
.ef-table th{
    background:#f1f5f9;color:var(--c-ink2);font-size:10.5px;font-weight:700;
    letter-spacing:.05em;text-transform:uppercase;padding:7px 8px;
    border-bottom:2px solid var(--c-border);border-right:1px solid var(--c-border);
    white-space:nowrap;text-align:left;
}
.ef-table th:last-child{border-right:none;}
.ef-table td{
    padding:2px 3px;border-bottom:1px solid var(--c-border);
    border-right:1px solid var(--c-border);vertical-align:middle;
}
.ef-table td:last-child{border-right:none;padding:2px 4px;text-align:center;}
.ef-table .form-group{margin:0;}
.ef-table .form-control{
    height:28px;padding:3px 6px;font-size:12px;
    border:none;border-radius:0;background:transparent;box-shadow:none;
}
.ef-table .form-control:focus{
    background:#fffbeb;border:1px solid #f59e0b;
    box-shadow:none;border-radius:2px;z-index:1;position:relative;
}
.ef-table .has-error .form-control{background:#fff1f2;border:1px solid var(--c-red);}
.ef-table .help-block{font-size:10px;color:var(--c-red);padding:1px 4px;margin:0;}
.ef-table .help-block:empty{display:none;}
.ef-table tbody tr:hover td{background:#f8fafc;}
.ef-table tbody tr:hover td .form-control{background:transparent;}
.ef-table tbody tr:hover td .form-control:focus{background:#fffbeb;}
/* service row accent */
.ef-table tbody tr.service td:first-child{border-left:3px solid #3b82f6;}
.ef-table tbody tr.service td{background:#eff6ff;}
.ef-table tbody tr.service:hover td{filter:brightness(.97);}
/* ── Select2 error state in table ── */
.ef-table .has-error .select2-container--bootstrap4 .select2-selection--single{
    border:1px solid var(--c-red) !important;
    background:#fff1f2 !important;
}
.ef-del-btn{
    display:inline-flex;align-items:center;justify-content:center;
    width:26px;height:26px;border-radius:var(--r);
    color:var(--c-ink3);border:1px solid transparent;
    text-decoration:none;transition:all .15s;cursor:pointer;font-size:12px;
}
.ef-del-btn:hover{background:#fff1f2;color:var(--c-red);border-color:#fecdd3;}
.ef-total-bar{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 16px;background:var(--c-bg);
    border-top:2px solid var(--c-border);gap:12px;flex-wrap:wrap;
}
.ef-total-label{font-size:11px;font-weight:600;color:var(--c-ink2);}
.ef-total-input{
    font-size:13px;font-weight:700;color:var(--c-ink);
    border:1px solid var(--c-border);border-radius:var(--r);
    padding:5px 10px;text-align:right;background:#fff;height:30px;width:130px;
}
.ef-total-actions{display:flex;align-items:center;gap:8px;}
.ef-totals-group{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
.ef-select2-cell .select2-container{width:100% !important;}
.ef-select2-cell .select2-container--bootstrap4 .select2-selection--single{
    height:28px !important;line-height:26px !important;
    border:none !important;border-radius:0 !important;
    background:transparent !important;font-size:12px;
}
.ef-select2-cell .select2-container--bootstrap4 .select2-selection__arrow{height:26px !important;}
');
?>

<div class="ef-wrap">
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($invoiceModel, 'document_type')->hiddenInput(['value' => $action])->label(false) ?>
    <?= $form->field($invoiceModel, 'id')->hiddenInput()->label(false) ?>

    <!-- ══ 1. Документ ══ -->
    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Документ</h3>
        </div>
        <div class="ef-section-body">
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'order_num')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Order №') ?>
                <?= $form->field($invoiceModel, 'order_date')->textInput(['value' => $invoiceModel->order_date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Order Date') ?>
            </div>
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'bill')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Adv Invoice №') ?>
                <?= $form->field($invoiceModel, 'bill_date')->textInput(['value' => $invoiceModel->bill_date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Adv Invoice Date') ?>
            </div>
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'invoice')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Invoice №') ?>
                <?= $form->field($invoiceModel, 'date')->textInput(['value' => $invoiceModel->date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Invoice Date') ?>
            </div>
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'contract')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Договір №') ?>
                <?= $form->field($invoiceModel, 'contract_date')->textInput(['value' => $invoiceModel->contract_date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Дата договору') ?>
            </div>
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'sek_rate')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Курс валюти') ?>
                <?= $form->field($invoiceModel, 'date_customs')->textInput(['value' => $invoiceModel->date_customs ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Дата митного оформлення') ?>
            </div>
            <div class="ef-pair">
                <?php $statusArr = Yii::$app->params['statuses']; ?>
                <?= $form->field($invoiceModel, 'status')->dropDownList(
                    $statusArr,
                    ['prompt' => '— оберіть статус —', 'class' => 'form-control select2bs4', 'style' => 'width:100%']
                )->label('Статус') ?>
                <div></div>
            </div>
        </div>
    </div>

    <!-- ══ 2. Постачальник ══ -->
    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Постачальник</h3>
        </div>
        <div class="ef-section-body">
            <div class="ef-customer-row">
                <?= $form->field($invoiceModel, 'customer_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(
                        \app\models\Customer::find()->where(['type' => 'supplier'])->orderBy(['name' => SORT_ASC])->all(),
                        'id', 'name'
                    ),
                    ['prompt' => '— виберіть постачальника —', 'class' => 'form-control select2bs4', 'style' => 'width:100%']
                )->label('Постачальник') ?>
                <?= Html::button('+ Новий', ['class' => 'ef-btn ef-btn-info btn-add-new-customer']) ?>
            </div>

            <div style="display:none;margin-top:12px">
                <?= $form->field($invoiceModel, 'newCustomer')->textInput(['class' => 'form-control'])->label('Новий постачальник') ?>
            </div>

            <div style="margin-top:12px">
                <?= $form->field($invoiceModel, 'comment')->textarea(['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Коментар…'])->label('Коментар') ?>
            </div>
        </div>
    </div>

    <!-- ══ 3. Файли ══ -->
    <?php if (!$invoiceModel->isNewRecord && !empty($invoiceModel->id)): ?>
    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Файли</h3>
        </div>
        <div class="ef-section-body">
            <?php if (!empty($invoiceModel->attachments)): ?>
                <?php
                $img = []; $json = [];
                foreach ($invoiceModel->attachments as $attachment) {
                    $root  = '/files/' . $invoiceModel->id . '/';
                    $img[] = $root . $attachment->filename;
                    $type  = pathinfo($attachment->filename, PATHINFO_EXTENSION);
                    $json[] = [
                        'type'    => in_array($type, ['jpg', 'jpeg']) ? 'image' : $type,
                        'caption' => $attachment->filename,
                        \yii\helpers\Url::to(['/attachment/delete-upload']),
                        'key'     => 'filename ' . $attachment->id,
                    ];
                }
                ?>
                <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
                    'options'       => ['accept' => '', 'multiple' => true],
                    'pluginOptions' => [
                        'showDownload' => true, 'initialPreviewAsData' => true,
                        'showCancel' => false, 'showPreview' => true,
                        'initialPreview' => $img, 'initialPreviewConfig' => $json,
                        'previewSettings' => ['image' => ['width' => 'auto', 'height' => 'auto', 'max-width' => '100%', 'max-height' => '100%']],
                        'previewFileType' => 'any', 'uploadAsync' => true,
                        'deleteUrl' => \yii\helpers\Url::to(['/attachment/delete-upload']),
                        'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
                        'uploadExtraData' => ['entity_id' => $invoiceModel->id, 'entity_type' => \app\models\Attachment::INVOICE],
                    ],
                ])->label(false) ?>
            <?php else: ?>
                <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
                    'options'       => ['accept' => '', 'multiple' => true],
                    'pluginOptions' => [
                        'showCancel' => false, 'previewFileType' => 'any',
                        'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
                        'uploadExtraData' => ['entity_id' => $invoiceModel->id, 'entity_type' => \app\models\Attachment::INVOICE],
                    ],
                ])->label(false) ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══ 4. Оплати ══ -->
    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Оплати</h3>
            <?= Html::a('+ Додати', 'javascript:void(0)', [
                'class'              => 'ef-btn ef-btn-info ef-btn-sm btn-add-payment',
                'data-payment-count' => count($invoiceModel->payments),
            ]) ?>
        </div>
        <div style="overflow-x:auto;">
            <table id="paymentItems" class="ef-table">
                <thead>
                    <tr id="paymentHeader" style="display:<?= empty($invoiceModel->payments) ? 'none' : '' ?>">
                        <th style="width:110px">Дата</th>
                        <th>Опис</th>
                        <th style="width:130px">Категорія</th>
                        <th style="width:170px">Контрагент</th>
                        <th style="width:100px">Сума</th>
                        <th style="width:32px"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($invoiceModel->payments as $i => $payment): ?>
                    <tr data-id="<?= $payment->id ?? $i ?>">
                        <td style="display:none">
                            <?= $form->field($payment, "[$i]id")->hiddenInput()->label(false) ?>
                            <?= $form->field($payment, "[$i]direction")->hiddenInput()->label(false) ?>
                            <?= $form->field($payment, "[$i]status")->hiddenInput()->label(false) ?>
                            <?= $form->field($payment, "[$i]currency")->hiddenInput(['value' => 'uah'])->label(false) ?>
                        </td>
                        <td><?= $form->field($payment, "[$i]date")->textInput(['value' => $payment->date ?? '', 'type' => 'date', 'class' => 'form-control'])->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]description")->textInput(['value' => $payment->description ?? '', 'class' => 'form-control'])->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]category_id")->dropDownList(
                            \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                            ['prompt' => '—', 'class' => 'form-control']
                        )->label(false) ?></td>
                        <td class="ef-select2-cell"><?= $form->field($payment, "[$i]customer_id")->dropDownList(
                            \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                            ['prompt' => '—', 'class' => 'form-control select2bs4', 'style' => 'width:100%']
                        )->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]amount")->textInput(['value' => $payment->amount ?? '', 'class' => 'form-control'])->label(false) ?></td>
                        <td>
                            <a href="#" class="ef-del-btn delete-payment" data-id="<?= $payment->id ?? $i ?>" data-item="<?= $payment->id ?? '' ?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot id="paymentTotal" style="display:<?= empty($invoiceModel->payments) ? 'none' : '' ?>">
                    <tr>
                        <td colspan="4" style="text-align:right;font-weight:600;padding:6px 8px;border-top:2px solid #e2e8f0;color:#475569;">РАЗОМ</td>
                        <td style="font-weight:700;padding:6px 8px;border-top:2px solid #e2e8f0;" id="paymentTotalAmount"><?= number_format(array_sum(array_map(function($p) { return (float)$p->amount; }, $invoiceModel->payments)), 2, '.', ' ') ?></td>
                        <td style="border-top:2px solid #e2e8f0;"></td>
                    </tr>
                </tfoot>
            </table>
            <?php if (empty($invoiceModel->payments)): ?>
                <div style="padding:20px;text-align:center;color:#94a3b8;font-size:12px;">Оплат поки немає</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ══ 5. Позиції ══ -->
    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Позиції</h3>
            <div style="display:flex;gap:6px;">
                <?= Html::a('+ Товар', 'javascript:void(0)', ['class' => 'ef-btn ef-btn-info ef-btn-sm btn-add-product', 'data-count' => $count, 'data-service' => '0']) ?>
                <?= Html::a('+ Сервіс', 'javascript:void(0)', ['class' => 'ef-btn ef-btn-info ef-btn-sm btn-add-service', 'data-count' => $count, 'data-service' => '1']) ?>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table id="invoiceItems" class="ef-table">
                <thead>
                    <tr>
                        <th style="width:90px">Артикул</th>
                        <th style="min-width:180px">Продукт</th>
                        <th style="min-width:130px">Новий продукт</th>
                        <th style="width:75px">Кількість</th>
                        <th style="width:95px">Ціна, SEK</th>
                        <th style="width:95px">Surcharge, SEK</th>
                        <th style="width:95px">Вартість, SEK</th>
                        <th style="width:32px"></th>
                    </tr>
                </thead>
                <tbody>
                <?php $totalSekAmount = $totalGoodsSekAmount = 0; ?>
                <?php foreach ($itemModels as $i => $item): ?>
                    <?php $isService = !empty($item['service']); ?>
                    <tr data-id="<?= $i ?>" class="<?= $isService ? 'service' : '' ?>">
                        <td style="display:none">
                            <?= $form->field($item, "[$i]id")->hiddenInput()->label(false) ?>
                            <?= $form->field($item, "[$i]service")->hiddenInput()->label(false) ?>
                        </td>
                        <td><?= $form->field($item, "[$i]articul")->textInput(['class' => 'form-control articul'])->label(false) ?></td>
                        <td class="ef-select2-cell">
                            <div class="form-group" style="margin:0">
                                <?= $form->field($item, "[$i]product_id")->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                    ['prompt' => '— оберіть —', 'class' => 'form-control product-id select2bs4', 'style' => 'width:100%']
                                )->label(false) ?>
                            </div>
                        </td>
                        <td><?= $form->field($item, "[$i]new_product")->label(false) ?></td>
                        <td><?= $form->field($item, "[$i]quantity")->textInput(['class' => 'form-control quantity', 'type' => 'number', 'min' => '0', 'step' => '1'])->label(false) ?></td>
                        <td><?= $form->field($item, "[$i]price_sek")->textInput(['class' => 'form-control price_sek'])->label(false) ?></td>
                        <td><?= $form->field($item, "[$i]surcharge_sek")->textInput(['class' => 'form-control surcharge_sek'])->label(false) ?></td>
                        <td><?= $form->field($item, "[$i]total_sek")->textInput(['class' => 'form-control total_sek'])->label(false) ?></td>
                        <td>
                            <a href="#" class="ef-del-btn delete-item" data-id="<?= $i ?>" data-item="<?= $item->id ?? '' ?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $totalSekAmount      += $item->price_sek * $item->quantity; ?>
                    <?php $totalGoodsSekAmount += !$item['service'] ? $item->price_sek * $item->quantity : 0; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot id="invoiceItemsTotal" style="display:<?= empty($itemModels) ? 'none' : '' ?>">
                    <tr>
                        <td colspan="6" style="text-align:right;font-weight:600;padding:6px 8px;border-top:2px solid #e2e8f0;color:#475569;">Сума SEK</td>
                        <td style="font-weight:700;padding:6px 8px;border-top:2px solid #e2e8f0;" id="invoiceTotalSekAmount"><?= number_format($totalSekAmount, 2, '.', ' ') ?></td>
                        <td style="border-top:2px solid #e2e8f0;"></td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align:right;font-weight:600;padding:6px 8px;color:#475569;">Товари SEK</td>
                        <td style="font-weight:700;padding:6px 8px;" id="invoiceTotalSekGoodsAmount"><?= number_format($totalGoodsSekAmount, 2, '.', ' ') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="ef-total-bar">
            <div class="ef-total-actions">
                <?= Html::submitButton('Зберегти', ['class' => 'ef-btn ef-btn-primary']) ?>
            </div>
            <div style="display:none">
                <?= $form->field($invoiceModel, 'total_sek_amount')->textInput([
                    'value' => $totalSekAmount,
                    'id'    => 'invoice-total_sek_amount',
                ])->label(false) ?>
                <?= $form->field($invoiceModel, 'total_sek_goods_amount')->textInput([
                    'value' => $totalGoodsSekAmount,
                    'id'    => 'invoice-total_sek_goods_amount',
                ])->label(false) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>

<?php
$_allProducts = \app\models\Product::find()->select(['id', 'articul'])->all();
$_productArticulMap = [];
$_articulProductMap = [];
foreach ($_allProducts as $_p) {
    $_productArticulMap[(string)$_p->id] = (string)($_p->articul ?? '');
    if (!empty($_p->articul)) {
        $_articulProductMap[mb_strtolower($_p->articul)] = (int)$_p->id;
    }
}
$_productArticulJson = json_encode($_productArticulMap, JSON_UNESCAPED_UNICODE);
$_articulProductJson = json_encode($_articulProductMap, JSON_UNESCAPED_UNICODE);

$scriptArticul = <<<JS
    var productArticulMap = {$_productArticulJson};
    var articulProductMap = {$_articulProductJson};

    /* заповнити артикули при завантаженні сторінки */
    \$('#invoiceItems tbody tr').each(function() {
        var rowId      = \$(this).data('id');
        var productId  = String(\$('#invoiceitem-' + rowId + '-product_id').val());
        var articulInp = \$('#invoiceitem-' + rowId + '-articul');
        if (productId && !articulInp.val() && productArticulMap[productId]) {
            articulInp.val(productArticulMap[productId]);
        }
    });

    /* product вибраний → заповнити артикул */
    \$(document).on('select2:select', '#invoiceItems .product-id', function() {
        var productId = String(\$(this).val());
        var rowId     = \$(this).closest('tr').data('id');
        var articul   = productArticulMap[productId] || '';
        \$('#invoiceitem-' + rowId + '-articul').val(articul);
    });

    /* артикул введений → знайти і вибрати продукт */
    \$(document).on('blur', '#invoiceItems .articul', function() {
        var articul  = \$(this).val().trim().toLowerCase();
        var rowId    = \$(this).closest('tr').data('id');
        var found    = articulProductMap[articul];
        if (articul && found !== undefined) {
            var selectEl = \$('#invoiceitem-' + rowId + '-product_id');
            selectEl.val(String(found)).trigger('change');
        }
    });
JS;

$scriptCalculate = <<<JS
    function recalcSekTotals() {
        var total = 0, totalFree = 0;
        $('.total_sek').each(function(){
            total += Number($(this).val());
            if (!$(this).parent().closest('tr').hasClass('service')) {
                totalFree += Number($(this).val());
            }
        });
        $('#invoice-total_sek_amount').val(total);
        $('#invoice-total_sek_goods_amount').val(totalFree);
        $('#invoiceTotalSekAmount').text(total.toFixed(2));
        $('#invoiceTotalSekGoodsAmount').text(totalFree.toFixed(2));
        $('#invoiceItemsTotal').css('display', $('.total_sek').length > 0 ? '' : 'none');
    }
    $(document).on("change input keyup", '.price_sek, .total_sek, .quantity, .surcharge_sek', function () {
        recalcSekTotals();
    });

    $(document).on("change input", '.price_sek', function () {
        var rowId           = $(this).closest('tr').data('id');
        var quantity_elem   = $('#invoiceitem-' + rowId + '-quantity');
        var price_sek_elem  = $('#invoiceitem-' + rowId + '-price_sek');
        var total_sek_elem  = $('#invoiceitem-' + rowId + '-total_sek');
        var surcharge_elem  = $('#invoiceitem-' + rowId + '-surcharge_sek');
        if (quantity_elem.val() !== '' && quantity_elem.val() !== 0) {
            if (price_sek_elem.val() !== '' && price_sek_elem.val() !== 0) {
                var surchargeVal = Number(surcharge_elem.val()) || 0;
                var quantityVal  = Number(quantity_elem.val());
                var priceVal     = Number(price_sek_elem.val());
                total_sek_elem.val((priceVal * quantityVal + surchargeVal * quantityVal).toFixed(2));
            } else {
                total_sek_elem.val(0);
            }
        }
    });

    $(document).on("change input", '.total_sek', function () {
        var rowId           = $(this).closest('tr').data('id');
        var quantity_elem   = $('#invoiceitem-' + rowId + '-quantity');
        var price_sek_elem  = $('#invoiceitem-' + rowId + '-price_sek');
        var total_sek_elem  = $('#invoiceitem-' + rowId + '-total_sek');
        var surcharge_elem  = $('#invoiceitem-' + rowId + '-surcharge_sek');
        if (quantity_elem.val() !== '' && quantity_elem.val() !== 0) {
            if (total_sek_elem.val() !== '' && total_sek_elem.val() !== 0) {
                var surchargeVal = Number(surcharge_elem.val()) || 0;
                var quantityVal  = Number(quantity_elem.val());
                var totalSekVal  = Number(total_sek_elem.val());
                price_sek_elem.val((totalSekVal / quantityVal - surchargeVal / quantityVal).toFixed(2));
            } else {
                price_sek_elem.val(0);
            }
        }
    });

    $(document).on("change input", '.quantity', function () {
        var rowId           = $(this).closest('tr').data('id');
        var quantity_elem   = $('#invoiceitem-' + rowId + '-quantity');
        var price_sek_elem  = $('#invoiceitem-' + rowId + '-price_sek');
        var total_sek_elem  = $('#invoiceitem-' + rowId + '-total_sek');
        var surcharge_elem  = $('#invoiceitem-' + rowId + '-surcharge_sek');
        if (quantity_elem.val() !== '' && quantity_elem.val() !== 0) {
            var surchargeVal = Number(surcharge_elem.val()) || 0;
            var quantityVal  = Number(quantity_elem.val());
            var priceVal     = Number(price_sek_elem.val());
            var totalSekVal  = Number(total_sek_elem.val());
            if (totalSekVal !== 0 && quantityVal !== 0) {
                price_sek_elem.val((totalSekVal / quantityVal - surchargeVal / quantityVal).toFixed(2));
            } else if (priceVal !== 0 && quantityVal !== 0) {
                total_sek_elem.val((priceVal * quantityVal + surchargeVal * quantityVal).toFixed(2));
            }
        }
    });

    $(document).on("change input", '.surcharge_sek', function () {
        var rowId           = $(this).closest('tr').data('id');
        var quantity_elem   = $('#invoiceitem-' + rowId + '-quantity');
        var price_sek_elem  = $('#invoiceitem-' + rowId + '-price_sek');
        var total_sek_elem  = $('#invoiceitem-' + rowId + '-total_sek');
        var surcharge_elem  = $('#invoiceitem-' + rowId + '-surcharge_sek');
        var surchargeVal    = Number(surcharge_elem.val()) || 0;
        var quantityVal     = Number(quantity_elem.val());
        var totalSekVal     = Number(total_sek_elem.val());
        var priceVal        = Number(price_sek_elem.val());
        if (totalSekVal !== 0 && quantityVal !== 0) {
            price_sek_elem.val((totalSekVal / quantityVal - surchargeVal / quantityVal).toFixed(2));
        } else if (priceVal !== 0 && quantityVal !== 0) {
            total_sek_elem.val((priceVal * quantityVal + surchargeVal * quantityVal).toFixed(2));
        }
    });

    $(document).on('change input', '#invoice-custom_taxes, #invoice-transport_fee, #invoice-brocker_fee, #invoice-bank_fee, #invoice-additional_cost, #invoice-sek_rate', function() {
        var custom_taxes    = Number($('#invoice-custom_taxes').val());
        var transport_fee   = Number($('#invoice-transport_fee').val());
        var brocker_fee     = Number($('#invoice-brocker_fee').val());
        var additional_cost = Number($('#invoice-additional_cost').val());
        var bank_fee        = Number($('#invoice-bank_fee').val());
        var sek_rate        = Number($('#invoice-sek_rate').val());
        $('#invoice-customsexpances').val(custom_taxes + transport_fee + brocker_fee + bank_fee + additional_cost * sek_rate);
    });

    $(document).on("click", '.btn-add-new-customer', function () {
        $('.field-invoice-newcustomer').parent().show();
        $('#invoice-customer_id').empty();
        $('.field-invoice-customer_id').parent().hide();
        $('.btn-add-new-customer').parent().hide();
    });
JS;

$script = <<<JS
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    $('.btn-add-product, .btn-add-service').on('click', function() {
        var ths     = $(this);
        var cnt     = ths.attr('data-count');
        var service = ths.attr('data-service');
        $.ajax({
            dataType: 'html',
            data:     { cnt: cnt, service: service },
            success: function(data) {
                $('#invoiceItems').find('tbody').append(data);
                $('.btn-add-product, .btn-add-service').attr('data-count', parseInt(cnt) + 1);
            },
            type: 'post',
            url:  '/invoice/add-import'
        });
    });

    $('.btn-add-payment').on('click', function() {
        var ths = $(this);
        var cnt = ths.attr('data-payment-count');
        $('#paymentHeader').css({ display: '' });
        $.ajax({
            dataType: 'html',
            data:     { cnt: cnt },
            success: function(data) {
                $('#paymentItems').find('tbody').append(data);
                ths.attr('data-payment-count', parseInt(cnt) + 1);
                $('#paymentTotal').show();
                updatePaymentTotal();
            },
            type: 'post',
            url:  '/invoice/add-import-payment'
        });
    });
JS;

$scriptDelete = <<<JS
    $(document).on('click', '.delete-item', function(event) {
        event.preventDefault();
        var id        = $(this).data('id');
        var invoiceId = $('#invoice-id').val();
        $('#invoiceItems').find('tr[data-id=' + id + ']').remove();
        recalcSekTotals();
        if (id > 0) {
            $.ajax({
                data: { id: id, invoiceId: invoiceId },
                success: function() {},
                type: 'post',
                url:  '/invoice/erase'
            });
        }
    });

    $(document).on('click', '.delete-payment', function(event) {
        event.preventDefault();
        var id        = $(this).data('id');
        var invoiceId = $('#invoice-id').val();
        $('#paymentItems').find('tr[data-id=' + id + ']').remove();
        updatePaymentTotal();
        if ($('.delete-payment').length < 1) {
            $('#paymentHeader').css({ display: 'none' });
            $('#paymentTotal').hide();
        }
        if (id > 0) {
            $.ajax({
                data: { id: id, invoiceId: invoiceId },
                success: function() {},
                type: 'post',
                url:  '/invoice/deletepayment'
            });
        }
    });
JS;

$scriptFormat = <<<JS
    function efFormatAmount(val) {
        val = val.trim().replace(/[\s\u00a0\u202f']/g, '').replace(',', '.');
        var num = parseFloat(val);
        return isNaN(num) ? '' : num.toFixed(2);
    }
    function updatePaymentTotal() {
        var total = 0;
        $('#paymentItems tbody input[name*="[amount]"]:not([type=hidden])').each(function() {
            var v = parseFloat($(this).val().replace(/[\s\u00a0\u202f']/g, '').replace(',', '.'));
            if (!isNaN(v)) total += v;
        });
        $('#paymentTotalAmount').text(total.toFixed(2));
    }
    $(document).on('blur', '.payment-amount, .price_sek, .surcharge_sek, .total_sek', function() {
        var formatted = efFormatAmount($(this).val());
        if (formatted !== '') $(this).val(formatted);
        if ($(this).closest('#paymentItems').length) updatePaymentTotal();
    });
    $(document).on('input', '.quantity', function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
    $(document).on('input', '.price_sek, .surcharge_sek, .total_sek', function() {
        $(this).val($(this).val().replace(/[^0-9.,]/g, ''));
    });
    $(document).on('blur', '.quantity', function() {
        var v = parseInt($(this).val(), 10);
        $(this).val(isNaN(v) || v < 0 ? '' : String(v));
    });
    $(document).on('input change', '#paymentItems input[name*="[amount]"]:not([type=hidden])', function() {
        updatePaymentTotal();
    });
JS;

$scriptValidation = <<<JS
    $('form').on('beforeSubmit', function() {
        var valid = true;
        var requiredPaymentFields = ['date', 'description', 'category_id', 'customer_id', 'amount'];

        $('#paymentItems tbody tr').each(function() {
            var row = $(this);
            requiredPaymentFields.forEach(function(field) {
                var input = row.find('[name*="[' + field + ']"]').not('[type=hidden]');
                if (!input.length) return;
                var fg = input.closest('.form-group');
                if (!input.val() || input.val() === '') {
                    fg.addClass('has-error');
                    fg.find('.help-block').text('Поле не може бути порожнім');
                    valid = false;
                } else {
                    fg.removeClass('has-error');
                    fg.find('.help-block').text('');
                }
            });
        });

        if (!valid) {
            $('#paymentItems')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return valid;
    });

    $(document).on('input change', '#paymentItems [name*="[date]"], #paymentItems [name*="[description]"], #paymentItems [name*="[category_id]"], #paymentItems [name*="[customer_id]"], #paymentItems [name*="[amount]"]', function() {
        if ($(this).val() !== '') {
            $(this).closest('.form-group').removeClass('has-error').find('.help-block').text('');
        }
    });

    /* Select2 fires change on the hidden <select> — catch it separately */
    $(document).on('select2:select', '#paymentItems .select2-hidden-accessible', function() {
        $(this).closest('.form-group').removeClass('has-error').find('.help-block').text('');
    });
JS;

$this->registerJs($script,           yii\web\View::POS_READY);
$this->registerJs($scriptDelete,     yii\web\View::POS_READY);
$this->registerJs($scriptCalculate,  yii\web\View::POS_READY);
$this->registerJs($scriptFormat,     yii\web\View::POS_READY);
$this->registerJs($scriptValidation, yii\web\View::POS_READY);
$this->registerJs($scriptArticul,    yii\web\View::POS_READY);
?>
