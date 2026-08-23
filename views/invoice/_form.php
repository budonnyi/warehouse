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
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-header">
            <!--        <h2 class="card-title">-->
            <?php //= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?><!--</h2>-->
        </div>
        <div class="card-header">
            <?= $form->field($invoiceModel, 'document_type')->hiddenInput(['value' => $action])->label(false) ?>
            <?= $form->field($invoiceModel, 'id')->hiddenInput()->label(false) ?>
            <div class="row">
                <div class="col-md-6 col-6">
                    <?= $form->field($invoiceModel, 'invoice')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 col-6">
                    <?= $form->field($invoiceModel, "date")->textInput(['value' => $invoiceModel->date ?? '', 'type' => 'date', 'class' => 'form-control']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-6">
                    <?= $form->field($invoiceModel, 'bill')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 col-6">
                    <?= $form->field($invoiceModel, "bill_date")->textInput(['value' => $invoiceModel->bill_date ?? '', 'type' => 'date', 'class' => 'form-control']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-6">
                    <?= $form->field($invoiceModel, 'contract')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 col-6">
                    <?= $form->field($invoiceModel, "contract_date")->textInput(['value' => $invoiceModel->contract_date ?? '', 'type' => 'date', 'class' => 'form-control']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php $statusArr = Yii::$app->params['statuses'] ?>
                    <?= $form->field($invoiceModel, "status")->dropDownList(
                        $statusArr,
                        ['prompt' => 'Оберіть статус', 'class' => 'select2bs4', 'style' => 'width: 100%']
                    ) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($invoiceModel, "customer_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Виберіть контрагента', 'class' => 'form-control select2bs4', 'style' => "width: 100%;"]
                    ) ?>
                </div>
                <div class="col-md-2" style="margin-top: 32px">
                    <?= Html::button('Новий покупець',
                        ['class' => 'btn btn-info btn-add-new-customer', 'style' => "white-space: nowrap; width: 100%"]) ?>
                </div>
                <div class="col-md-6" style="display: none">
                    <?= $form->field($invoiceModel, 'newCustomer')->textInput()->label('Новий покупець') ?>
                </div>
                <div class="col-md-4" style="margin-top: 32px">
                    <?= $form->field($invoiceModel, 'through')->textInput(['placeholder' => 'Через'])->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($invoiceModel, 'comment')->textarea(['rows' => '2']) ?>
                </div>
            </div>
        </div>
    </div>

<?php if (!empty($invoiceModel->attachments) && !$invoiceModel->isNewRecord && !empty($invoiceModel->id)): ?>
    <div class="card">
        <div class="card-header">
            <!--        <h2 class="card-title">-->
            <?php //= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?><!--</h2>-->
        </div>
        <div class="card-header">
            <?php
            $img = [];
            $json = [];
            foreach ($invoiceModel->attachments as $attachment) {
                $root = '/files/' . $invoiceModel->id . '/';
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
                        'entity_id' => $invoiceModel->id,
                        'entity_type' => \app\models\Attachment::INVOICE
                    ],
                ]
            ]) ?>
        </div>
    </div>
<?php elseif (!empty($invoiceModel->id)) : ?>
    <div class="card">
        <div class="card-header">
            <!--        <h2 class="card-title">-->
            <?php //= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?><!--</h2>-->
        </div>
        <div class="card-header">
            <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::classname(), [
                'options' => ['accept' => '', 'multiple' => true],
                'pluginOptions' => [
                    'showCancel' => false,
                    'previewFileType' => 'any',
                    'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
                    'uploadExtraData' => [
                        'entity_id' => $invoiceModel->id,
                        'entity_type' => \app\models\Attachment::INVOICE
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php endif; ?>

    <div class="card">
        <div class="card-header">
            <!--        <h2 class="card-title">-->
            <?php //= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?><!--</h2>-->
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <table id="paymentItems" style="width:100%">
                <thead>
                <tr id="paymentHeader" style="display: <?= empty($invoiceModel->payments) ? 'none' : '' ?>">
                    <th style="width: 7%">Дата</th>
                    <th style="width: 20%">Опис</th>
                    <th style="width: 5%">Хто кому</th>
                    <th style="width: 5%">Категорія</th>
                    <th style="width: 5%">Назва</th>
                    <th style="width: 5%">Сума</th>
                    <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($invoiceModel->payments as $i => $payment) { ?>
                        <div class="row">
                    <tr data-id="<?= $i ?>" class="payment-row">
                        <?= $form->field($payment, "[$i]id")->hiddenInput()->label(false); ?>
                        <?= $form->field($payment, "[$i]currency")->hiddenInput()->label(false); ?>
                        <!--                --><?php //= $form->field($payment, "[$i]direction")->hiddenInput()->label(false); ?>
                        <?= $form->field($payment, "[$i]status")->hiddenInput()->label(false); ?>
                        <td><?= $form->field($payment, "[$i]date")->textInput(['value' => $payment->date ?? '', 'type' => 'date', 'class' => 'form-control payment-date', 'data-number' => $i])->label(false); ?></td>
                        <td><?= $form->field($payment, "[$i]description")->textInput(['value' => $payment->description ?? '', 'class' => 'form-control payment-description', 'data-number' => $i])->label(false); ?></td>
                        <td><?= $form->field($payment, "[$i]direction")->dropDownList(['income' => 'Надходженя', 'payment' => 'Оплата'],
                                ['prompt' => 'Select the direction', 'class' => 'form-control payment-direction', 'data-number' => $i])->label(false); ?></td>
                        <td><?= $form->field($payment, "[$i]category_id")->dropDownList(
                                \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                                ['prompt' => 'Select the product', 'class' => 'form-control payment-category-id', 'data-number' => $i]
                            )->label(false); ?></td>
                        <td><?= $form->field($payment, "[$i]customer_id")->dropDownList(
                                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                ['prompt' => 'Select the customer', 'class' => 'form-control payment-customer-id select2bs4', 'style' => "width: 100%;", 'data-number' => $i]
                            )->label(false); ?></td>
                        <td><?= $form->field($payment, "[$i]amount")->textInput(['value' => $payment->amount ?? '', 'class' => "form-control payment-amount", 'data-number' => $i])->label(false); ?></td>
                        <td><a href="#" class="nav-link delete-payment" data-id="<?= $i ?>"
                               data-item="<?= $payment->id ?? '' ?>"
                               style="margin-bottom: 20px;">
                                <i class="far fa-trash-alt"></i>
                            </a></td>
                    </tr>
                        </div>
                <?php } ?>
                </tbody>
            </table>
            <?= Html::a('Додати оплату', 'javascript:void(0)',
                ['class' => 'btn btn-info btn-add-payment', 'data-payment-count' => count($invoiceModel->payments)]) ?>

        </div>
    </div>

    <style>
        .delete-payment, .do-payment {
            cursor: pointer
        }
    </style>

    <div class="card">
        <div class="card-header">
            <!--        <h2 class="card-title">-->
            <?php //= (Yii::t('app', 'Накладна') . ' №' . $model->invoice ?? '') . (!empty($model->date) ? ' від ' . date('d.m.Y', strtotime($model->date)) : ''); ?><!--</h2>-->
        </div>
        <div class="direct-chat-messages" style="height: 100%;">
            <table id="invoiceItems" style="width:100%">
                <thead>
                <tr>
                    <!--            <th style="width: 10%">ID</th>-->
                    <th style="width: 10%">Артикул</th>
                    <th style="width: 20%">Продукт</th>
                    <th style="width: 20%">Новий продукт</th>
                    <th style="width: 15%">Кількість</th>
                    <th style="width: 20%">Ціна</th>
                    <th style="width: 5%"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemModels as $i => $item): ?>
                    <tr data-id="<?= $i ?>">
                        <?= $form->field($item, "[$i]id")->hiddenInput()->label(false); ?>
                        <td><?= $form->field($item, "[$i]articul")->textInput(['class' => 'form-control articul-field', 'value' => $item->products->articul ?? ''])->label(false); ?></td>
                        <td>
                            <div class="form-group">
                                <?= Html::dropDownList("InvoiceItem[$i][product_id]", $item['product_id'],
                                    \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                                    ['prompt' => 'Select the product', 'class' => 'form-control product-id select2bs4', 'style' => "width: 100%;"]
                                ) ?>
                            </div>
                        </td>
                        <td><?= $form->field($item, "[$i]new_product")->label(false); ?></td>
                        <td><?= $form->field($item, "[$i]quantity")->textInput(['class' => 'form-control quantity-field'])->label(false); ?></td>
                        <td><?= $form->field($item, "[$i]price")->textInput(['class' => 'form-control price-field'])->label(false); ?></td>
                        <td><a href="#" class="nav-link delete-item" data-id="<?= $i ?>"
                               data-item="<?= $item->id ?? '' ?>"
                               style="margin-bottom: 20px;">
                                <i class="far fa-trash-alt"></i>
                            </a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="form-group">
                <?= Html::a('Додати продукт', 'javascript:void(0)',
                    ['class' => 'btn btn-info btn-add-product', 'data-count' => $count]) ?>
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
                <input type="text" id="invoiceTotalUah" class="form-control" name="Invoice[total_amount]"
                       value="<?= $invoiceModel->total_amount ?? 0 ?>"
                       style="width: 22%;float: right;margin-right: 74px;">
                <div style="float: right;"><h5 style="line-height: 36px;font-weight: 600; margin-right: 15px">
                        Разом: </h5></div>
            </div>
        </div>
    </div>

    <style>
        input {
            position: relative;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            background-position: right;
            background-size: auto;
            cursor: pointer;
            position: absolute;
            bottom: 3;
            left: 0;
            right: 0;
            top: 3;
            width: auto;
        }
    </style>
<?php ActiveForm::end(); ?>

    <div class="row"></div>

<?php $productArr = \app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(); ?>
<?php $resultArr = []; ?>
<?php foreach ($productArr as $item) {
    $resultArr[$item->id] = $item->attributes;
} ?>

    <script>
        var productArr = <?php print(json_encode($resultArr)) ?>;
        const newRecord = "<?= empty($invoiceModel->id) ?>";
    </script>

<?php
$scriptCalculate = <<< JS


    $(document).on('change', '.product-id', function() {
        const productId = $(this).val();
        const element = $(this).parent().parent().parent('tr');
        element.find('.articul-field').val(productArr[productId]['articul']);
        element.find('.quantity-field').val('1');
        element.find('.price-field').val(productArr[productId]['price']);
        recalculate();
    });

    $('#invoiceItems').on('input change', function() {
        recalculate();
    });

    $(document).on("click", '.btn-add-new-customer', function () {
         $('.field-invoice-newcustomer').parent().show();
         $('#invoice-customer_id').empty();
         $('.field-invoice-customer_id').parent().hide();
         $('.btn-add-new-customer').parent().hide();
         console.log($('.field-invoice-through').parent());
         $('.field-invoice-through').parent().removeClass('col-md-3').addClass('col-md-6');
    });
    
    function recalculate() {
        const table = $(document).find('#invoiceItems tr');
        var total = 0;
        $.each(table, function() {
            var rowId = $(this).data('id');
            if (typeof rowId !== 'undefined') {
                const price = Number($('#invoiceitem-' +rowId+ '-price').val());
                const quantity = Number($('#invoiceitem-' +rowId+ '-quantity').val());
                total += price * quantity;
            }
        })
        
        total = total.toFixed(2)
        $('#invoiceTotalUah').val(total);
    }
JS;

$script = <<< JS
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    
    $('.btn-add-product').on('click', function() {
      var ths = $(this);  
      var cnt = ths.attr('data-count');

      $.ajax({
            'dataType' : 'html',
            'data': {'cnt': cnt},
            'success' : function(data) {
                $('#invoiceItems').find('tbody').append(data);
                // $('#ajax_forms').append(data);
                ths.attr('data-count', parseInt(cnt)+1);
            },
            'type' : 'post',
            'url' : '/invoice/add'
        });
    });
    
    $('.datepicker').datepicker({
    // language: "ru",
    // autoclose: true,
    // format: "dd.mm.yyyy",
    // // startDate: '-3d',
    //        uiLibrary: 'bootstrap5'
    });

    $('.btn-add-payment').on('click', function() {
      var ths = $(this);  
      var cnt = ths.attr('data-payment-count');
      $('#paymentHeader').css({'display': ''});
      $.ajax({
            'dataType' : 'html',
            'data': {'cnt': cnt},
            'success' : function(data) {
                $('#paymentItems').find('tbody').append(data);
                ths.attr('data-payment-count', parseInt(cnt)+1);
            },
            'type' : 'post',
            'url' : '/invoice/add-payment'
        });
    });
    
    // update ajax
    $('.payment-date, .payment-description, .payment-direction, .payment-category-id, .payment-customer-id, .payment-amount').on('change', function() {
        if (!newRecord) {
            const number = $(this).data('number');
            const id = $('#payment-' + number + '-id').val();
            const date = $('#payment-' + number + '-date').val();
            const description = $('#payment-' + number + '-description').val();
            const direction = $('#payment-' + number + '-direction').val();
            const category_id = $('#payment-' + number + '-category_id').val();
            const customer_id = $('#payment-' + number + '-customer_id').val();
            const amount = $('#payment-' + number + '-amount').val();
            $.ajax({
                'dataType' : 'html',
                'data': {id, date, description, direction, category_id, customer_id, amount},
                'success' : function(data) {
                },
                'type' : 'post',
                'url' : '/payment/update-ajax'
            });
        }
    });
    
    $(document).on('click', '.do-payment', function(event) {
      event.preventDefault();
      const _this = $(this);
      const number = $(this).data('id');
      const invoice_id = $('#invoice-id').val();
      const date = $('#payment-' + number + '-date').val();
      const description = $('#payment-' + number + '-description').val();
      const direction = $('#payment-' + number + '-direction').val();
      const category_id = $('#payment-' + number + '-category_id').val();
      const customer_id = $('#payment-' + number + '-customer_id').val();
      const amount = $('#payment-' + number + '-amount').val();
      const currency = $('#payment-' + number + '-currency').val();
      $.ajax({
            'dataType' : 'html',
            'data': {invoice_id, date, description, direction, category_id, customer_id, amount, currency},
            'success' : function(data) {
               _this.remove();
            },
            'type' : 'post',
            'url' : '/payment/create-ajax'
        });
    });     
    
    $('#invoice-status').on('change', function() {
        const status = $(this).val();
        const invoiceId = $('#invoice-id').val();
        if (!newRecord) {
            $.ajax({
                'dataType' : 'html',
                'data': {'invoiceId': invoiceId, 'status': status},
                'success' : function(data) {
                    console.log('status updated');
                    // $('#paymentItems').find('tbody').append(data);
                    // ths.attr('data-payment-count', parseInt(cnt)+1);
                },
                'type' : 'post',
                'url' : '/invoice/ajax-invoice-update'
            });
        }
    });
JS;

$scriptDelete = <<< JS
    $(document).on('click', '.delete-item', function(event) {
        event.preventDefault();
      const id = $(this).data('id');  
      const invoiceId = $('#invoice-id').val(); 

      $('#invoiceItems').find('tr[data-id=' + id + ']').remove();

      if (id > 0) 
          $.ajax({
                'data': {id, invoiceId},
                'success' : function() {
                    console.log('Deleted');
                },
                'type' : 'post',
                'url' : '/invoice/erase'
          });
    });

    $(document).on('click', '.delete-payment', function(event) {
      event.preventDefault();
      const id = $(this).data('id');  
      const paymentId = $(this).data('item');  
      const invoiceId = $('#invoice-id').val(); 

      $('#paymentItems').find('tr[data-id=' + id + ']').remove();

      if ($('.delete-payment').length < 1) 
          $('#paymentHeader').css({'display': 'none'});

      if (id > 0) 
          $.ajax({
                'data': {paymentId, invoiceId},
                'success' : function() {
                    console.log('Deleted');
                },
                'type' : 'post',
                'url' : '/payment/erase'
          });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJs($scriptDelete, yii\web\View::POS_READY);
$this->registerJs($scriptCalculate, yii\web\View::POS_READY);
