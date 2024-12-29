<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

yii\bootstrap4\BootstrapAsset::register($this);
yii\bootstrap4\BootstrapPluginAsset::register($this);
yii\web\YiiAsset::register($this);

/** @var yii\web\View $this */
/** @var app\models\Invoice $invoiceModel */
/** @var yii\widgets\ActiveForm $form */

$action = $action ?? $invoiceModel->document_type;

?>
    <!--    <script-->
    <!--            src="https://code.jquery.com/jquery-3.7.1.min.js"-->
    <!--            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="-->
    <!--            crossorigin="anonymous"></script>-->
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($invoiceModel, 'document_type')->hiddenInput(['value' => $action])->label(false) ?>
<?= $form->field($invoiceModel, 'id')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-md-6 col-6">
            <?= $form->field($invoiceModel, 'order_num')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6 col-6">
            <?= $form->field($invoiceModel, "order_date")->textInput(['value' => $invoiceModel->order_date ?? '', 'type' => 'date', 'class' => 'form-control']); ?>
        </div>
    </div>
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
            <?= $form->field($invoiceModel, 'sek_rate')->textInput(['maxlength' => true])->label('Курс валюти') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "date_customs")->textInput(['value' => $invoiceModel->date_customs ?? '', 'type' => 'date', 'class' => 'form-control']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php $statusArr = Yii::$app->params['statuses'] ?>
            <?= $form->field($invoiceModel, "status")->dropDownList(
                $statusArr,
                ['prompt' => 'Оберіть статус']
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "customer_id")->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->where(['type' => 'supplier'])->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Виберіть контрагента', 'class' => 'select2bs4', 'style' => 'width: 100%']
            )->label('Постачальник') ?>
        </div>
        <div class="col-md-2" style="margin-top: 32px">
            <?= Html::button('Новий постачальник',
                ['class' => 'btn btn-info btn-add-new-customer', 'style'=>"white-space: nowrap;"]) ?>
        </div>
        <div class="col-md-6" style="display: none">
            <?= $form->field($invoiceModel, 'newCustomer')->textInput()->label('Новий постачальник') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($invoiceModel, 'comment')->textarea(['rows' => '2']) ?>
        </div>
    </div>

<?php if (!empty($invoiceModel->attachments) && !$invoiceModel->isNewRecord && !empty($invoiceModel->id)): ?>
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
<?php elseif (!empty($invoiceModel->id)) : ?>
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
<?php endif; ?>

    <table id="paymentItems" style="width:100%">
        <thead>
        <tr id="paymentHeader" style="display: <?= empty($invoiceModel->payments) ? 'none' : '' ?>">
            <th style="width: 7%">Дата</th>
            <th style="width: 20%">Опис</th>
            <th style="width: 5%">Категорія</th>
            <th style="width: 5%">Контрагент</th>
            <th style="width: 5%">Валюта</th>
            <th style="width: 5%">Сума</th>
            <th style="width: 5%"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($invoiceModel->payments as $i => $payment) { ?>
            <tr data-id="<?= $payment->id ?? $i ?>">
                <?= $form->field($payment, "[$i]id")->hiddenInput()->label(false); ?>
                <?= $form->field($payment, "[$i]direction")->hiddenInput()->label(false); ?>
                <?= $form->field($payment, "[$i]status")->hiddenInput()->label(false); ?>
                <td><?= $form->field($payment, "[$i]date")->textInput(['value' => $payment->date ?? '', 'type' => 'date', 'class' => 'form-control'])->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]description")->textInput(['value' => $payment->description ?? ''])->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]category_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                        ['prompt' => 'Select the product']
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]customer_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Select the customer', 'class' => 'select2bs4', 'style' => "width: 100%;"]
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]currency")->dropDownList(
                        ['uah' => 'ГРН', 'sek' => 'SEK']
//                        ['prompt' => 'Валюта']
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]amount")->textInput(['value' => $payment->amount ?? ''])->label(false); ?></td>
                <td><a href="#" class="nav-link delete-payment" data-id="<?= $payment->id ?? $i ?>"
                       data-item="<?= $payment->id ?? '' ?>"
                       style="margin-bottom: 20px;">
                        <i class="far fa-trash-alt"></i>
                    </a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?= Html::a('Додати оплату', 'javascript:void(0)',
    ['class' => 'btn btn-info btn-add-payment', 'data-payment-count' => count($invoiceModel->payments)]) ?>

    <table id="invoiceItems" style="width:100%">
        <thead>
        <tr>
            <!--            <th style="width: 10%">ID</th>-->
            <th style="width: 10%">Артикул</th>
            <th style="width: 15%">Продукт</th>
            <th style="width: 15%">Новий продукт</th>
            <th style="width: 15%">Кількість</th>
            <th style="width: 15%">Ціна, SEK</th>
            <th style="width: 15%">Surcharge, SEK</th>
            <th style="width: 15%">Вартість, SEK</th>
            <th style="width: 5%"></th>
        </tr>
        </thead>
        <tbody>
        <style>
            .service :first-child {
                border: 1px solid blue;
                /*background-color: #00c4ff;*/
                /*border: 1px solid blue;*/
            }
        </style>
        <?php $totalSekAmount = $totalGoodsSekAmount = 0; //$invoiceModel->total_amount_sek; ?>
        <?php foreach ($itemModels as $i => $item): ?>
            <?php $isService = !empty($item['service']) ?>
            <tr data-id="<?= $i ?>" class="<?= $isService ? ' service' : '' ?>">
                <?= $form->field($item, "[$i]id")->hiddenInput()->label(false); ?>
                <?= $form->field($item, "[$i]service")->hiddenInput()->label(false); ?>
                <td><?= $form->field($item, "[$i]articul")->textInput(['class' => 'form-control articul'])->label(false); ?></td>
                <td><?= $form->field($item, "[$i]product_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Select the product', 'class' => 'form-control product-id select2bs4', 'style' => "width: 100%;"]
                    )->label(false); ?></td>
                <td><?= $form->field($item, "[$i]new_product")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]quantity")->textInput(['class' => 'form-control quantity'])->label(false); ?></td>
                <td><?= $form->field($item, "[$i]price_sek")->textInput(['class' => 'form-control price_sek'])->label(false); ?></td>
                <td><?= $form->field($item, "[$i]surcharge_sek")->textInput(['class' => 'form-control surcharge_sek'])->label(false); ?></td>
                <td><?= $form->field($item, "[$i]total_sek")->textInput(['class' => 'form-control total_sek'])->label(false); ?></td>
                <td><a href="#" class="nav-link delete-item" data-id="<?= $i ?>" data-item="<?= $item->id ?? '' ?>"
                       style="margin-bottom: 20px;">
                        <i class="far fa-trash-alt"></i>
                    </a></td>
            </tr>
            <?php $totalSekAmount += $item->price_sek * $item->quantity ?>
            <?php $totalGoodsSekAmount += !$item['service'] ? $item->price_sek * $item->quantity : 0 ?>
        <?php endforeach; ?>
        </tbody>
    </table>

<?= $form->field($invoiceModel, "total_sek_amount")->textInput(['value' => $totalSekAmount])->label(false); ?>
<?= $form->field($invoiceModel, "total_sek_goods_amount")->textInput(['value' => $totalGoodsSekAmount])->label(false); ?>

    <div class="form-group">
        <?= Html::a('Додати продукт', 'javascript:void(0)',
            ['class' => 'btn btn-info btn-add-product', 'data-count' => $count, 'data-service' => '0']) ?>
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Додати сервіс', 'javascript:void(0)',
            ['class' => 'btn btn-info btn-add-service', 'data-count' => $count, 'data-service' => '1']) ?>
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

<?php
$scriptCalculate = <<< JS

    $(document).on("change input keyup", '.price_sek, .price_sek, .total_sek, .quantity, .surcharge_sek', function () {
        let total = 0;
        let totalFree = 0;
        $('.total_sek').each(function(){
            total += Number($(this).val()); 
            if (!$(this).parent().closest('tr').hasClass('service')) {
                    totalFree += Number($(this).val()); 
                }
        })
        $('#invoice-total_sek_amount').val(total);
        $('#invoice-total_sek_goods_amount').val(totalFree);
    })
    
    $(document).on("change input", '.price_sek', function () {
         const rowId = $(this).closest('tr').data('id');
         console.log(rowId)
         var quantity_elem = $(document).find('#invoiceitem-'+rowId+'-quantity');
         var price_sek_elem = $(document).find('#invoiceitem-'+rowId+'-price_sek');
         var total_sek_elem = $(document).find('#invoiceitem-'+rowId+'-total_sek');
         var surcharge_sek_elem = $(document).find('#invoiceitem-'+rowId+'-surcharge_sek');
    
         if (quantity_elem.val() !== '' && quantity_elem.val() !== 0 ) {
             // input price
             if ($(this).hasClass('price_sek') && price_sek_elem.val() !== '' && price_sek_elem.val() !== 0) {
                 const surchargeVal = Number(surcharge_sek_elem.val()) || 0;
                 const quantityVal = Number(quantity_elem.val());
                 const priceVal = Number(price_sek_elem.val());
                 const totalSekVal = priceVal * quantityVal + surchargeVal * quantityVal;
                 total_sek_elem.val(totalSekVal.toFixed(2));
             } else {
                 total_sek_elem.val(0);
             }
         }
    });

    $(document).on("click", '.btn-add-new-customer', function () {
         $('.field-invoice-newcustomer').parent().show();
         $('#invoice-customer_id').empty();
         $('.field-invoice-customer_id').parent().hide();
         $('.btn-add-new-customer').parent().hide();
    });

    $(document).on("change input", '.total_sek', function () {
         const rowId = $(this).closest('tr').data('id');
         var quantity_elem = $(document).find('#invoiceitem-'+rowId+'-quantity');
         var price_sek_elem = $(document).find('#invoiceitem-'+rowId+'-price_sek');
         var total_sek_elem = $(document).find('#invoiceitem-'+rowId+'-total_sek');
         var surcharge_sek_elem = $(document).find('#invoiceitem-'+rowId+'-surcharge_sek');
    
         if (quantity_elem.val() !== '' && quantity_elem.val() !== 0 ) {
             // input total_sek
             if ($(this).hasClass('total_sek') && total_sek_elem.val() !== '' && total_sek_elem.val() !== 0) {
                 const surchargeVal = Number(surcharge_sek_elem.val()) || 0;
                 const quantityVal = Number(quantity_elem.val());
                 const totalSekVal = Number(total_sek_elem.val());
                 const priceVal = totalSekVal / quantityVal - surchargeVal / quantityVal;
                 price_sek_elem.val(priceVal.toFixed(2));
             } else {
                 price_sek_elem.val(0);
             }
         }
    });
    
    $(document).on("change input", '.quantity', function () {
         const rowId = $(this).closest('tr').data('id');
         var quantity_elem = $(document).find('#invoiceitem-'+rowId+'-quantity');
         var price_sek_elem = $(document).find('#invoiceitem-'+rowId+'-price_sek');
         var total_sek_elem = $(document).find('#invoiceitem-'+rowId+'-total_sek');
         var surcharge_sek_elem = $(document).find('#invoiceitem-'+rowId+'-surcharge_sek');
    
         if (quantity_elem.val() !== '' && quantity_elem.val() !== 0 ) {
             // input quantity
             if ($(this).hasClass('quantity') && quantity_elem.val() !== '' && quantity_elem.val() !== 0) {
                 const surchargeVal = Number(surcharge_sek_elem.val()) || 0;
                 const quantityVal = Number(quantity_elem.val());
                 const priceVal = Number(price_sek_elem.val());
                 const totalSekVal = Number(total_sek_elem.val());
                 if (totalSekVal !== '' && totalSekVal !== 0 && quantityVal !== 0) {
                    const priceVal = totalSekVal / quantityVal - surchargeVal / quantityVal;
                    price_sek_elem.val(priceVal.toFixed(2));
                 } else if (priceVal !== '' && priceVal !== 0 && quantityVal !== 0) {
                    const totalSekVal = priceVal * quantityVal + surchargeVal * quantityVal;
                    total_sek_elem.val(totalSekVal.toFixed(2));
                 }
             }
         }
    });
    
    $(document).on("change input", '.surcharge_sek', function () {
         const rowId = $(this).closest('tr').data('id');
         var quantity_elem = $(document).find('#invoiceitem-'+rowId+'-quantity');
         var price_sek_elem = $(document).find('#invoiceitem-'+rowId+'-price_sek');
         var total_sek_elem = $(document).find('#invoiceitem-'+rowId+'-total_sek');
         var surcharge_sek_elem = $(document).find('#invoiceitem-'+rowId+'-surcharge_sek');
         
         // input surcharge
         if ($(this).hasClass('surcharge_sek') && surcharge_sek_elem.val() !== '' && surcharge_sek_elem.val() !== 0) {
             const surchargeVal = Number(surcharge_sek_elem.val()) || 0;
             const quantityVal = Number(quantity_elem.val());
             const totalSekVal = Number(total_sek_elem.val());
             const priceVal = Number(total_sek_elem.val()) / quantity_elem.val() - surchargeVal / quantity_elem.val();
             if (totalSekVal !== '' && totalSekVal !== 0 && quantityVal !== 0) {
                    const priceVal = totalSekVal / quantityVal - surchargeVal / quantityVal;
                    price_sek_elem.val(priceVal.toFixed(2));
                 } else if (priceVal !== '' && priceVal !== 0 && quantityVal !== 0) {
                    const totalSekVal = priceVal * quantityVal + surchargeVal * quantityVal;
                    total_sek_elem.val(totalSekVal.toFixed(2));
                 }
         }
    });
    
    $(document).on('change input', '#invoice-custom_taxes, #invoice-transport_fee, #invoice-brocker_fee, #invoice-bank_fee, #invoice-additional_cost, #invoice-sek_rate', function() {
        const custom_taxes = Number($('#invoice-custom_taxes').val());
        const transport_fee = Number($('#invoice-transport_fee').val());
        const brocker_fee = Number($('#invoice-brocker_fee').val());
        const additional_cost = Number($('#invoice-additional_cost').val());
        const bank_fee = Number($('#invoice-bank_fee').val());
        const sek_rate = Number($('#invoice-sek_rate').val());
        
        const total = custom_taxes + transport_fee + brocker_fee + bank_fee + additional_cost * sek_rate;
        
        $('#invoice-customsexpances').val(total);
    })
JS;

$script = <<< JS
    $('.btn-add-product, .btn-add-service').on('click', function() {
      var ths = $(this);
      var cnt = ths.attr('data-count');
      var service = ths.attr('data-service');
      
      $.ajax({
            'dataType' : 'html',
            'data': {'cnt': cnt, 'service': service},
            'success' : function(data) {
                $('#invoiceItems').find('tbody').append(data);
                // $('#ajax_forms').append(data);
                $('.btn-add-product, .btn-add-service').attr('data-count', parseInt(cnt)+1);
                // ths.attr('data-count', parseInt(cnt)+1);
            },
            'type' : 'post',
            'url' : '/invoice/add-import'
        });
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
            'url' : '/invoice/add-import-payment'
        });
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
      const invoiceId = $('#invoice-id').val(); 

      $('#paymentItems').find('tr[data-id=' + id + ']').remove();
      
      if ($('.delete-payment').length < 1)
          $('#paymentHeader').css({'display': 'none'});
      
      if (id > 0) 
          $.ajax({
                'data': {id, invoiceId},
                'success' : function() {
                    console.log('Deleted');
                },
                'type' : 'post',
                'url' : '/invoice/deletepayment'
          });
    })
JS;

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJs($scriptDelete, yii\web\View::POS_READY);
$this->registerJs($scriptCalculate, yii\web\View::POS_READY);
