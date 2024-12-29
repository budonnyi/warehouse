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

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($invoiceModel, 'document_type')->hiddenInput(['value' => $action])->label(false) ?>
<?= $form->field($invoiceModel, 'id')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, 'invoice')->textInput(['maxlength' => true])->label('Інвойс') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "date")->widget(
                DatePicker::className(), [
                'value' => date('Y-m-d', time()),
                'options' => ['placeholder' => 'Вибери дату ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ])->label('Дата інвойсу'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, 'bill')->textInput(['maxlength' => true])->label('Інвойс предплата') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "bill_date")->widget(
                DatePicker::className(), [
                'value' => date('Y-m-d', time()),
                'options' => ['placeholder' => 'Вибери дату ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, 'contract')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "contract_date")->widget(
                DatePicker::className(), [
                'value' => date('Y-m-d', time()),
                'options' => ['placeholder' => 'Обери дату ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, 'sek_rate')->textInput(['maxlength' => true])->label('Курс валюти') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "date_customs")->widget(
                DatePicker::className(), [
                'value' => date('Y-m-d', time()),
                'options' => ['placeholder' => 'Вибери дату ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]); ?>
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
                ['prompt' => 'Виберіть контрагента']
            )->label('Постачальник') ?>
        </div>
        <div class="col-md-2" style="margin-top: 32px">
        <?= Html::button('Новий постачальник',
            ['class' => 'btn btn-info btn-add-new-customer']) ?>
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


<?php if (!empty($invoiceModel->attachments) && !$invoiceModel->isNewRecord): ?>
    <?php
    $url = Yii::$app->urlManager->baseUrl;
    $img = [];
    $json = [];

    foreach ($invoiceModel->attachments as $attachment) {
        $root = $url . '/files/' . $invoiceModel->id . '/';
        if (file_exists(Yii::getAlias('@app') . '/web/files/' . $invoiceModel->id . '/' . $attachment->filename)) {
            $img[] = $root . $attachment->filename;
            
            $json[] = [
                'caption' => $attachment->filename, \yii\helpers\Url::to(['/attachment/delete-upload']),
                'key' => 'filename ' . $attachment->id,
            ];
        }
    }
    ?>

    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
        'options' => ['accept' => '', 'multiple' => true],

        'pluginOptions' => [
//                'showRemove' => false,
//                'showUpload' => false,
        'initialPreviewAsData'=>true,
        'initialCaption'=>"The Moon and the Earth",
            'showCancel' => false,
//                'overwriteInitial' => false,
            'initialPreview' => $img,
            'initialPreviewConfig' => $json,
//                'previewFileType' => 'image',
            'previewFileType' => 'any',
            'uploadAsync' => true,
            'maxFileSize' => 3 * 1024 * 1024,
            'deleteUrl' => \yii\helpers\Url::to(['/attachment/delete-upload']),
            'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
            'uploadExtraData' => [
                'invoice_id' => $invoiceModel->id
            ],
            'enableResumableUpload' => true,
            'initialPreviewAsData' => true,
            'theme' => 'fa5',
//        deleteUrl: '/site/file-delete',
//        uploadExtraData: {
//        uploadToken: "SOME_VALID_TOKEN"
//        },
            'overwriteInitial' => false,
//        'initialPreviewConfig' => [
//            ['caption' => "Lorem Ipsum.txt", 'type' => "text", 'description' => "<h5>Content # 1</h5> Enjoy this Lorem Ipsum text.", size: 1430, url: "/site/file-delete", key: 12},
//            {type: "pdf", description: "<h5>Content # 2</h5> Enjoy this sample PDF document.", size: 8000, caption: "PDF Sample.pdf", url: "/site/file-delete", key: 14},
//            {type: "video", description: "<h5>Content # 3</h5> Enjoy this sample video.", size: 375000, filetype: "video/mp4", caption: "Krajee Sample.mp4", url: "/site/file-delete", key: 15}
//        ],
            'preferIconicPreview' => true,
            'previewFileIconSettings' => [ // configure your icon file extensions
                'doc' => '<i class="fas fa-file-word text-primary"></i>',
                'xls' => '<i class="fas fa-file-excel text-success"></i>',
                'ppt' => '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf' => '<i class="fas fa-file-pdf text-danger"></i>',
                'zip' => '<i class="fas fa-file-archive text-muted"></i>',
                'htm' => '<i class="fas fa-file-code text-info"></i>',
                'txt' => '<i class="fas fa-file-alt text-info"></i>',
                'mov' => '<i class="fas fa-file-video text-warning"></i>',
                'mp3' => '<i class="fas fa-file-audio text-warning"></i>',
                // note for these file types below no extension determination logic
                // has been configured (the keys itself will be used as extensions)
                'jpg' => '<i class="fas fa-file-image text-danger"></i>',
                'gif' => '<i class="fas fa-file-image text-muted"></i>',
                'png' => '<i class="fas fa-file-image text-primary"></i>'
            ],
//            'previewFileExtSettings' => [ // configure the logic for determining icon file extensions
//                'doc' => 'ext.match(/(doc|docx)$/i)',
//                'xls' => 'ext.match(/(xls|xlsx)$/i)',
//                'ppt' => 'ext.match(/(ppt|pptx)$/i)',
//                'zip' => 'ext.match(/(zip|rar|tar|gzip|gz|7z)$/i)',
//                'htm' => 'ext.match(/(htm|html)$/i)',
//                'txt'=> 'ext.match(/(txt|ini|csv|java|php|js|css)$/i)',
//                'mov'=> 'ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i)',
//                'mp3'=> 'ext.match(/(mp3|wav)$/i)',
//            ],

//                'allowedExtensions' => ['jpg','png','jpeg'],
        ]
    ]) ?>
<?php else : ?>
    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::classname(), [
        'options' => ['accept' => '', 'multiple' => true],
        'pluginOptions' => [
            'showCancel' => false,
//                'showUpload' => false,
            'previewFileType' => 'any',
            'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
            'uploadExtraData' => [
                'invoice_id' => $invoiceModel->id
            ],
        ],
    ]); ?>
<?php endif; ?>

<?php //echo '<label class="control-label">Add Attachments</label>';
//echo \kartik\file\FileInput::widget([
//    'model' => $invoiceModel->attachments,
//    'attribute' => 'attachment_1',
//    'options' => ['multiple' => true]
//]); ?>


    <table id="paymentItems" style="width:100%">
        <thead>
        <tr>
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
                <td><?= $form->field($payment, "[$i]date")->textInput(['value' => $payment->date ?? ''])->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]description")->textInput(['value' => $payment->description ?? ''])->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]category_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                        ['prompt' => 'Select the product']
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]customer_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Select the customer']
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]currency")->dropDownList(
                        ['uah' => 'ГРН', 'sek' => 'SEK']
//                        ['prompt' => 'Валюта']
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]amount")->textInput(['value' => $payment->amount ?? ''])->label(false); ?></td>
                <td><a href="#" class="nav-link delete-payment" data-id="<?= $payment->id ?? $i ?>" data-item="<?= $payment->id ?? '' ?>"
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
    <?php $totalSekAmount = 0; //$invoiceModel->total_amount_sek; ?>
        <?php foreach ($itemModels as $i => $item): ?>
            <tr data-id="<?= $i ?>">
                <?= $form->field($item, "[$i]id")->hiddenInput()->label(false); ?>
                <td><?= $form->field($item, "[$i]articul")->textInput(['class' => 'form-control articul'])->label(false); ?></td>
                <td><?= $form->field($item, "[$i]product_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Select the product']
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
        <?php endforeach; ?>
        </tbody>
    </table>

    <?= $form->field($invoiceModel, "total_sek_amount")->hiddenInput(['value' => $totalSekAmount])->label(false); ?>

    <div class="form-group">
        <?= Html::a('Додати продукт', 'javascript:void(0)',
            ['class' => 'btn btn-info btn-add-product', 'data-count' => $count]) ?>
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php
$scriptCalculate = <<< JS

    $(document).on("change input keyup", '.price_sek, .price_sek, .total_sek, .quantity, .surcharge_sek', function () {
        let total = 0;
        $('.total_sek').each(function(){
            total += Number($(this).val()); 
        })
        $('#invoice-total_sek_amount').val(total);
    })
    
    $(document).on("change input", '.price_sek', function () {
         const rowId = $(this).closest('tr').data('id');
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
            'url' : '/invoice/add-import'
        });
    });

    $('.btn-add-payment').on('click', function() {
      var ths = $(this);  
      var cnt = ths.attr('data-payment-count');

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
    $(document).on('click', '.delete-item', function() {
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
    })
    
    $(document).on('click', '.delete-payment', function() {
      const id = $(this).data('id');  
      const invoiceId = $('#invoice-id').val(); 

      $('#paymentItems').find('tr[data-id=' + id + ']').remove();

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

$this->registerJs($scriptCalculate, yii\web\View::POS_READY);
$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJs($scriptDelete, yii\web\View::POS_READY);
