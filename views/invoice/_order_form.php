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
            <?= $form->field($invoiceModel, 'document_type')->hiddenInput(['value' => $action])->label(false) ?>
            <?= $form->field($invoiceModel, 'id')->hiddenInput()->label(false) ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($invoiceModel, 'comment')->textarea(['rows' => '2']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
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
