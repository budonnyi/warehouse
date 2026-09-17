<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

yii\bootstrap4\BootstrapAsset::register($this);
yii\bootstrap4\BootstrapPluginAsset::register($this);
yii\web\YiiAsset::register($this);

/** @var yii\web\View $this */
/** @var app\models\Invoice $invoiceModel */
/** @var yii\widgets\ActiveForm $form */

$action = $action ?? $invoiceModel->document_type;

$this->registerCss('
/* ── Tokens ── */
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

/* ── Section card ── */
.ef-section{
    background:var(--c-white);
    border:1px solid var(--c-border);
    border-radius:6px;
    box-shadow:0 1px 3px rgba(15,23,42,.05);
    margin-bottom:12px;
    overflow:hidden;
}
.ef-section-head{
    display:flex;align-items:center;justify-content:space-between;
    padding:10px 16px;
    border-bottom:1px solid var(--c-border);
    background:var(--c-bg);
}
.ef-section-title{
    font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
    color:var(--c-ink3);margin:0;
}
.ef-section-body{padding:16px;}

/* ── Standard form fields ── */
.ef-wrap .form-group{margin-bottom:12px;}
.ef-wrap label{font-size:11px;font-weight:600;color:var(--c-ink2);margin-bottom:4px;display:block;letter-spacing:.02em;}
.ef-wrap .form-control{
    font-size:13px;color:var(--c-ink);
    border:1px solid #cbd5e1;border-radius:var(--r);
    padding:6px 10px;height:34px;
    transition:border-color .15s,box-shadow .15s;
    background:#fff;
}
.ef-wrap .form-control:focus{
    border-color:#60a5fa;
    box-shadow:0 0 0 2px rgba(96,165,250,.2);
    outline:none;
}
.ef-wrap textarea.form-control{height:auto;}
.ef-wrap .help-block{font-size:11px;color:var(--c-red);margin-top:3px;}

/* date picker */
.ef-wrap input[type="date"]{position:relative;}
.ef-wrap input[type="date"]::-webkit-calendar-picker-indicator{
    cursor:pointer;position:absolute;top:0;right:0;bottom:0;left:0;width:auto;
    background-position:right 8px center;background-size:14px;
}

/* ── Grid layouts ── */
.ef-pair{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
@media(max-width:576px){.ef-pair{grid-template-columns:1fr;}}

/* ── Customer row — all items align to bottom ── */
.ef-customer-row{
    display:grid;
    grid-template-columns:1fr auto auto;
    gap:12px;
    align-items:end;
}
.ef-customer-row .form-group{margin-bottom:0;}
@media(max-width:576px){.ef-customer-row{grid-template-columns:1fr;}}

/* ── Buttons ── */
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

/* ── Excel-style table ── */
.ef-table{width:100%;border-collapse:collapse;font-size:12px;}
.ef-table th{
    background:#f1f5f9;color:var(--c-ink2);
    font-size:10.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;
    padding:7px 8px;border-bottom:2px solid var(--c-border);
    border-right:1px solid var(--c-border);
    white-space:nowrap;text-align:left;
}
.ef-table th:last-child{border-right:none;}
.ef-table td{
    padding:2px 3px;
    border-bottom:1px solid var(--c-border);
    border-right:1px solid var(--c-border);
    vertical-align:middle;
}
.ef-table td:last-child{border-right:none;padding:2px 4px;text-align:center;}

/* cell inputs — look like editable table cells */
.ef-table .form-group{margin:0;}
.ef-table .form-control{
    height:28px;
    padding:3px 6px;
    font-size:12px;
    border:none;
    border-radius:0;
    background:transparent;
    box-shadow:none;
}
.ef-table .form-control:focus{
    background:#fffbeb;
    border:1px solid #f59e0b;
    box-shadow:none;
    border-radius:2px;
    z-index:1;
    position:relative;
}
/* validation error still visible in table */
.ef-table .has-error .form-control{background:#fff1f2;border:1px solid var(--c-red);}
.ef-table .help-block{font-size:10px;color:var(--c-red);padding:1px 4px;margin:0;}
.ef-table .help-block:empty{display:none;}

.ef-table tbody tr:hover td{background:#f8fafc;}
.ef-table tbody tr:hover td .form-control{background:transparent;}
.ef-table tbody tr:hover td .form-control:focus{background:#fffbeb;}

/* ── Delete button ── */
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

/* ── Total bar ── */
.ef-total-bar{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 16px;
    background:var(--c-bg);
    border-top:2px solid var(--c-border);
    gap:12px;flex-wrap:wrap;
}
.ef-total-label{font-size:12px;font-weight:600;color:var(--c-ink2);}
.ef-total-input{
    font-size:15px;font-weight:800;color:var(--c-ink);
    border:1px solid var(--c-border);border-radius:var(--r);
    padding:6px 12px;text-align:right;width:160px;
    background:#fff;height:34px;
}
.ef-total-actions{display:flex;align-items:center;gap:8px;}

/* ── select2 in table cell ── */
.ef-table .select2-container{width:100% !important;}
.ef-table .select2-container--bootstrap4 .select2-selection--single{
    height:28px !important;
    line-height:26px !important;
    border:none !important;
    border-radius:0 !important;
    background:transparent !important;
    font-size:12px;
}
.ef-table .select2-container--bootstrap4 .select2-selection--single:focus,
.ef-table td:focus-within .select2-selection--single{
    background:#fffbeb !important;
}
.ef-table .select2-container--bootstrap4 .select2-selection__arrow{height:26px !important;}
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
                <?= $form->field($invoiceModel, 'invoice')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Накладна №') ?>
                <?= $form->field($invoiceModel, 'date')->textInput(['value' => $invoiceModel->date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Дата накладної') ?>
            </div>
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'bill')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Рахунок №') ?>
                <?= $form->field($invoiceModel, 'bill_date')->textInput(['value' => $invoiceModel->bill_date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Дата рахунку') ?>
            </div>
            <div class="ef-pair">
                <?= $form->field($invoiceModel, 'contract')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Договір №') ?>
                <?= $form->field($invoiceModel, 'contract_date')->textInput(['value' => $invoiceModel->contract_date ?? '', 'type' => 'date', 'class' => 'form-control'])->label('Дата договору') ?>
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

    <!-- ══ 2. Контрагент ══ -->
    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Контрагент</h3>
        </div>
        <div class="ef-section-body">
            <div class="ef-customer-row">
                <?= $form->field($invoiceModel, 'customer_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(
                        \app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(),
                        'id', 'name'
                    ),
                    ['prompt' => '— виберіть контрагента —', 'class' => 'form-control select2bs4', 'style' => 'width:100%']
                )->label('Покупець') ?>

                <?= Html::button('+ Новий', ['class' => 'ef-btn ef-btn-info btn-add-new-customer']) ?>

                <?= $form->field($invoiceModel, 'through')->textInput(['placeholder' => 'Через кого', 'class' => 'form-control'])->label('Через') ?>
            </div>

            <div style="display:none;margin-top:12px">
                <?= $form->field($invoiceModel, 'newCustomer')->textInput(['class' => 'form-control'])->label('Новий покупець') ?>
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
                $img  = [];
                $json = [];
                foreach ($invoiceModel->attachments as $attachment) {
                    $root   = '/files/' . $invoiceModel->id . '/';
                    $img[]  = $root . $attachment->filename;
                    $type   = pathinfo($attachment->filename, PATHINFO_EXTENSION);
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
                        'showDownload'         => true,
                        'initialPreviewAsData' => true,
                        'showCancel'           => false,
                        'showPreview'          => true,
                        'initialPreview'       => $img,
                        'initialPreviewConfig' => $json,
                        'previewSettings'      => [
                            'image' => ['width' => 'auto', 'height' => 'auto', 'max-width' => '100%', 'max-height' => '100%'],
                        ],
                        'previewFileType' => 'any',
                        'uploadAsync'     => true,
                        'deleteUrl'       => \yii\helpers\Url::to(['/attachment/delete-upload']),
                        'uploadUrl'       => \yii\helpers\Url::to(['/attachment/files-upload']),
                        'uploadExtraData' => [
                            'entity_id'   => $invoiceModel->id,
                            'entity_type' => \app\models\Attachment::INVOICE,
                        ],
                    ],
                ])->label(false) ?>
            <?php else: ?>
                <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
                    'options'       => ['accept' => '', 'multiple' => true],
                    'pluginOptions' => [
                        'showCancel'      => false,
                        'previewFileType' => 'any',
                        'uploadUrl'       => \yii\helpers\Url::to(['/attachment/files-upload']),
                        'uploadExtraData' => [
                            'entity_id'   => $invoiceModel->id,
                            'entity_type' => \app\models\Attachment::INVOICE,
                        ],
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
                'class'              => 'ef-btn ef-btn-info btn-add-payment',
                'data-payment-count' => count($invoiceModel->payments),
            ]) ?>
        </div>
        <div style="overflow-x:auto;">
            <table id="paymentItems" class="ef-table">
                <thead>
                    <tr id="paymentHeader" style="display:<?= empty($invoiceModel->payments) ? 'none' : '' ?>">
                        <th style="width:110px">Дата</th>
                        <th>Опис</th>
                        <th style="width:120px">Напрямок</th>
                        <th style="width:130px">Категорія</th>
                        <th style="width:170px">Контрагент</th>
                        <th style="width:100px">Сума</th>
                        <th style="width:32px"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($invoiceModel->payments as $i => $payment): ?>
                    <tr data-id="<?= $i ?>" class="payment-row">
                        <?php /* hidden inputs inside <td> to avoid browser ejecting divs from <tr> */ ?>
                        <td style="display:none">
                            <?= $form->field($payment, "[$i]id")->hiddenInput()->label(false) ?>
                            <?= $form->field($payment, "[$i]currency")->hiddenInput()->label(false) ?>
                            <?= $form->field($payment, "[$i]status")->hiddenInput()->label(false) ?>
                        </td>
                        <td><?= $form->field($payment, "[$i]date")->textInput(['value' => $payment->date ?? '', 'type' => 'date', 'class' => 'form-control payment-date', 'data-number' => $i])->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]description")->textInput(['value' => $payment->description ?? '', 'class' => 'form-control payment-description', 'data-number' => $i])->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]direction")->dropDownList(
                            ['income' => 'Надходження', 'payment' => 'Оплата'],
                            ['prompt' => '—', 'class' => 'form-control payment-direction', 'data-number' => $i]
                        )->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]category_id")->dropDownList(
                            \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                            ['prompt' => '—', 'class' => 'form-control payment-category-id', 'data-number' => $i]
                        )->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]customer_id")->dropDownList(
                            \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                            ['prompt' => '—', 'class' => 'form-control payment-customer-id select2bs4', 'style' => 'width:100%', 'data-number' => $i]
                        )->label(false) ?></td>
                        <td><?= $form->field($payment, "[$i]amount")->textInput(['value' => $payment->amount ?? '', 'class' => 'form-control payment-amount', 'data-number' => $i])->label(false) ?></td>
                        <td>
                            <a href="#" class="ef-del-btn delete-payment" data-id="<?= $i ?>" data-item="<?= $payment->id ?? '' ?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot id="paymentTotal" style="display:<?= empty($invoiceModel->payments) ? 'none' : '' ?>">
                    <tr>
                        <td colspan="5" style="text-align:right;font-weight:600;padding:6px 8px;border-top:2px solid #e2e8f0;color:#475569;">РАЗОМ</td>
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
            <?= Html::a('+ Додати', 'javascript:void(0)', [
                'class'      => 'ef-btn ef-btn-info btn-add-product',
                'data-count' => $count,
            ]) ?>
        </div>
        <div style="overflow-x:auto;">
            <table id="invoiceItems" class="ef-table">
                <thead>
                    <tr>
                        <th style="width:100px">Артикул</th>
                        <th style="min-width:220px">Продукт</th>
                        <th style="min-width:140px">Новий продукт</th>
                        <th style="width:80px">Кількість</th>
                        <th style="width:110px">Ціна</th>
                        <th style="width:32px"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($itemModels as $i => $item): ?>
                    <tr data-id="<?= $i ?>">
                        <td style="display:none">
                            <?= $form->field($item, "[$i]id")->hiddenInput()->label(false) ?>
                        </td>
                        <td><?= $form->field($item, "[$i]articul")->textInput(['class' => 'form-control articul-field', 'value' => $item->products->articul ?? ''])->label(false) ?></td>
                        <td>
                            <div class="form-group">
                                <?= Html::dropDownList(
                                    "InvoiceItem[$i][product_id]",
                                    $item['product_id'],
                                    \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                    ['prompt' => '— оберіть —', 'class' => 'form-control product-id select2bs4', 'style' => 'width:100%']
                                ) ?>
                            </div>
                        </td>
                        <td><?= $form->field($item, "[$i]new_product")->label(false) ?></td>
                        <td><?= $form->field($item, "[$i]quantity")->textInput(['class' => 'form-control quantity-field'])->label(false) ?></td>
                        <td><?= $form->field($item, "[$i]price")->textInput(['class' => 'form-control price-field'])->label(false) ?></td>
                        <td>
                            <a href="#" class="ef-del-btn delete-item" data-id="<?= $i ?>" data-item="<?= $item->id ?? '' ?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ef-total-bar">
            <div class="ef-total-actions">
                <?= Html::submitButton('Зберегти', ['class' => 'ef-btn ef-btn-primary']) ?>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="ef-total-label">Разом:</span>
                <input type="text" id="invoiceTotalUah" class="ef-total-input" name="Invoice[total_amount]"
                       value="<?= $invoiceModel->total_amount ?? 0 ?>" readonly>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
</div>

<?php $productArr = \app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(); ?>
<?php $resultArr  = []; ?>
<?php foreach ($productArr as $item) { $resultArr[$item->id] = $item->attributes; } ?>

<script>
    var productArr = <?= json_encode($resultArr) ?>;
    const newRecord = "<?= (int)empty($invoiceModel->id) ?>";
</script>

<?php
$scriptCalculate = <<<JS
    $(document).on('change', '.product-id', function() {
        var productId = $(this).val();
        var element   = $(this).closest('tr');
        element.find('.articul-field').val(productArr[productId]['articul']);
        element.find('.quantity-field').val('1');
        element.find('.price-field').val(productArr[productId]['price']);
        recalculate();
    });

    $('#invoiceItems').on('input change', function() {
        recalculate();
    });

    $(document).on('click', '.btn-add-new-customer', function() {
        $('.field-invoice-newcustomer').parent().show();
        $('#invoice-customer_id').empty();
        $('.field-invoice-customer_id').parent().hide();
        $('.btn-add-new-customer').parent().hide();
        $('.field-invoice-through').parent().removeClass('col-md-3').addClass('col-md-6');
    });

    function recalculate() {
        var total = 0;
        $('#invoiceItems tbody tr').each(function() {
            var rowId = $(this).data('id');
            if (typeof rowId !== 'undefined') {
                var price    = Number($('#invoiceitem-' + rowId + '-price').val());
                var quantity = Number($('#invoiceitem-' + rowId + '-quantity').val());
                total += price * quantity;
            }
        });
        $('#invoiceTotalUah').val(total.toFixed(2));
    }
JS;

$script = <<<JS
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    $('.btn-add-product').on('click', function() {
        var ths = $(this);
        var cnt = ths.attr('data-count');
        $.ajax({
            dataType: 'html',
            data:     { cnt: cnt },
            success: function(data) {
                $('#invoiceItems').find('tbody').append(data);
                ths.attr('data-count', parseInt(cnt) + 1);
            },
            type: 'post',
            url:  '/invoice/add'
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
            url:  '/invoice/add-payment'
        });
    });

    $('.payment-date, .payment-description, .payment-direction, .payment-category-id, .payment-customer-id, .payment-amount').on('change', function() {
        if (!newRecord) {
            var number      = $(this).data('number');
            var id          = $('#payment-' + number + '-id').val();
            var date        = $('#payment-' + number + '-date').val();
            var description = $('#payment-' + number + '-description').val();
            var direction   = $('#payment-' + number + '-direction').val();
            var category_id = $('#payment-' + number + '-category_id').val();
            var customer_id = $('#payment-' + number + '-customer_id').val();
            var amount      = $('#payment-' + number + '-amount').val();
            $.ajax({
                dataType: 'html',
                data:     { id: id, date: date, description: description, direction: direction, category_id: category_id, customer_id: customer_id, amount: amount },
                success:  function() {},
                type:     'post',
                url:      '/payment/update-ajax'
            });
        }
    });

    $(document).on('click', '.do-payment', function(event) {
        event.preventDefault();
        var _this       = $(this);
        var number      = $(this).data('id');
        var invoice_id  = $('#invoice-id').val();
        var date        = $('#payment-' + number + '-date').val();
        var description = $('#payment-' + number + '-description').val();
        var direction   = $('#payment-' + number + '-direction').val();
        var category_id = $('#payment-' + number + '-category_id').val();
        var customer_id = $('#payment-' + number + '-customer_id').val();
        var amount      = $('#payment-' + number + '-amount').val();
        var currency    = $('#payment-' + number + '-currency').val();
        $.ajax({
            dataType: 'html',
            data:     { invoice_id: invoice_id, date: date, description: description, direction: direction, category_id: category_id, customer_id: customer_id, amount: amount, currency: currency },
            success:  function() { _this.remove(); },
            type:     'post',
            url:      '/payment/create-ajax'
        });
    });

    $('#invoice-status').on('change', function() {
        var status    = $(this).val();
        var invoiceId = $('#invoice-id').val();
        if (!newRecord) {
            $.ajax({
                dataType: 'html',
                data:     { invoiceId: invoiceId, status: status },
                success:  function() {},
                type:     'post',
                url:      '/invoice/ajax-invoice-update'
            });
        }
    });
JS;

$scriptDelete = <<<JS
    $(document).on('click', '.delete-item', function(event) {
        event.preventDefault();
        var id        = $(this).data('id');
        var invoiceId = $('#invoice-id').val();
        $('#invoiceItems').find('tr[data-id=' + id + ']').remove();
        recalculate();
        if (id > 0) {
            $.ajax({
                data:    { id: id, invoiceId: invoiceId },
                success: function() {},
                type:    'post',
                url:     '/invoice/erase'
            });
        }
    });

    $(document).on('click', '.delete-payment', function(event) {
        event.preventDefault();
        var id        = $(this).data('id');
        var paymentId = $(this).data('item');
        var invoiceId = $('#invoice-id').val();
        $('#paymentItems').find('tr[data-id=' + id + ']').remove();
        updatePaymentTotal();
        if ($('.delete-payment').length < 1) {
            $('#paymentHeader').css({ display: 'none' });
            $('#paymentTotal').hide();
        }
        if (id > 0) {
            $.ajax({
                data:    { paymentId: paymentId, invoiceId: invoiceId },
                success: function() {},
                type:    'post',
                url:     '/payment/erase'
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
   function efSanitizeAmountInput(val) {
        // залишаємо тільки цифри, крапки та коми
        val = val.replace(/[^0-9.,]/g, '');
        // всі коми -> крапки
        val = val.replace(/,/g, '.');
        // лишаємо тільки першу крапку
        var firstDot = val.indexOf('.');
        if (firstDot !== -1) {
            val = val.slice(0, firstDot + 1) + val.slice(firstDot + 1).replace(/\./g, '');
        }
        return val;
    }
    $(document).on('input', '#paymentItems input[name*="[amount]"]:not([type=hidden])', function() {
        var input     = this;
        var oldVal    = $(input).val();
        var oldCaret  = input.selectionStart;
        var newVal    = efSanitizeAmountInput(oldVal);
        if (newVal !== oldVal) {
            var removedBeforeCaret = oldVal.slice(0, oldCaret).length - efSanitizeAmountInput(oldVal.slice(0, oldCaret)).length;
            $(input).val(newVal);
            var newCaret = Math.max(0, oldCaret - removedBeforeCaret);
            input.setSelectionRange(newCaret, newCaret);
        }
        updatePaymentTotal();
    });
    $(document).on('change', '#paymentItems input[name*="[amount]"]:not([type=hidden])', function() {
        updatePaymentTotal();
    });
    $(document).on('input', '.price-field', function() {
        var pos = this.selectionStart;
        var cleaned = this.value.replace(/[^0-9.,]/g, '');
        if (cleaned !== this.value) {
            this.value = cleaned;
            this.setSelectionRange(pos - 1, pos - 1);
        }
    });

    $(document).on('blur', '.payment-amount, .price-field', function() {
        var formatted = efFormatAmount($(this).val());
        if (formatted !== '') $(this).val(formatted);
        if ($(this).closest('#paymentItems').length) updatePaymentTotal();
    });
    $(document).on('input change', '#paymentItems input[name*="[amount]"]:not([type=hidden])', function() {
        updatePaymentTotal();
    });
JS;

$scriptValidation = <<<JS
    $('form').on('beforeSubmit', function() {
        var valid = true;
        var requiredPaymentFields = ['date', 'description', 'direction', 'category_id', 'customer_id', 'amount'];

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

        $('#invoiceItems tbody tr').each(function() {
            var priceInp = $(this).find('.price-field');
            if (!priceInp.length) return;
            var fg = priceInp.closest('.form-group');
            if (priceInp.val() === '') {
                fg.addClass('has-error');
                fg.find('.help-block').text('Поле не може бути порожнім');
                valid = false;
            } else {
                fg.removeClass('has-error');
                fg.find('.help-block').text('');
            }
        });

        if (!valid) {
            $('#paymentItems, #invoiceItems')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return valid;
    });

    $(document).on('input change', '#paymentItems [name*="[date]"], #paymentItems [name*="[description]"], #paymentItems [name*="[direction]"], #paymentItems [name*="[category_id]"], #paymentItems [name*="[customer_id]"], #paymentItems [name*="[amount]"]', function() {
        if ($(this).val() !== '') {
            $(this).closest('.form-group').removeClass('has-error').find('.help-block').text('');
        }
    });

    $(document).on('input', '#invoiceItems .price-field', function() {
        if ($(this).val() !== '') {
            $(this).closest('.form-group').removeClass('has-error').find('.help-block').text('');
        }
    });

    /* Select2 fires change on the hidden <select> — catch it separately */
    $(document).on('select2:select', '#paymentItems .select2-hidden-accessible', function() {
        $(this).closest('.form-group').removeClass('has-error').find('.help-block').text('');
    });
JS;

$this->registerJs($script,          yii\web\View::POS_READY);
$this->registerJs($scriptDelete,    yii\web\View::POS_READY);
$this->registerJs($scriptCalculate, yii\web\View::POS_READY);
$this->registerJs($scriptFormat,    yii\web\View::POS_READY);
$this->registerJs($scriptValidation, yii\web\View::POS_READY);
?>
