<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

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
            <?= $form->field($invoiceModel, 'invoice')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "date")->widget(
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
            <?= $form->field($invoiceModel, 'bill')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "bill_date")->widget(
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
            <?php $statusArr = Yii::$app->params['statuses'] ?>
            <?= $form->field($invoiceModel, "status")->dropDownList(
                $statusArr,
                ['prompt' => 'Оберіть статус']
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php echo $form->field($invoiceModel, 'customer_id')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'Виберіть контрагента'],
                    'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?php /*= $form->field($invoiceModel, "customer_id")->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Виберіть контрагента']
            ) */?>
        </div>
        <div class="col-md-3">
            <?= $form->field($invoiceModel, 'newCustomer')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($invoiceModel, 'through')->textInput() ?>
        </div>
    </div>

    <table id="paymentItems" style="width:100%">
    <thead>
    <tr>
        <th style="width: 7%">Дата</th>
        <th style="width: 20%">Опис</th>
        <th style="width: 5%">Категорія</th>
        <th style="width: 5%">Сума</th>
        <th style="width: 5%"></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($invoiceModel->payments as $i => $payment) { ?>
            <tr data-id="<?= $i ?>">
                <?= $form->field($payment, "[$i]id")->hiddenInput()->label(false); ?>
                <?= $form->field($payment, "[$i]currency")->hiddenInput()->label(false); ?>
                <?= $form->field($payment, "[$i]direction")->hiddenInput()->label(false); ?>
                <?= $form->field($payment, "[$i]status")->hiddenInput()->label(false); ?>
                <td><?= $form->field($payment, "[$i]date")->textInput(['value' => $payment->date ?? ''])->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]description")->textInput(['value' => $payment->description ?? ''])->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]category_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                        ['prompt' => 'Select the product']
                    )->label(false); ?></td>
                <td><?= $form->field($payment, "[$i]amount")->textInput(['value' => $payment->amount ?? ''])->label(false); ?></td>
                <td><a href="#" class="nav-link delete-payment" data-id="<?= $i ?>" data-item="<?= $payment->id ?? '' ?>"
                       style="margin-bottom: 20px;">
                        <i class="far fa-trash-alt"></i>
                    </a></td>
            </tr>
        <?php } ?>
    </tbody>
    </table>
        <?= Html::a('Додати оплату', 'javascript:void(0)',
            ['class' => 'btn btn-info btn-add-payment', 'data-payment-count' => count($invoiceModel->payments)]) ?>
    <style>
        .containerFlex {
            display: flex;
            padding: 5px;
            border: 1px solid #000
        }
        .flexChild {
            flex: 1;
            margin: 5px;
            border: 1px solid #ccc;
            text-align: center;
            font-weight: 700;
        }
        .tableDiv {
            display:inline-block
        }
    </style>
<div id="productItems">
    <div class="productHeader containerFlex" style="">
        <div class="flexChild 1tableDiv" style="min-width:10%">Артикул</div>
        <div class="flexChild 1tableDiv" style="min-width:15%">Продукт</div>
        <div class="flexChild 1tableDiv" style="min-width:15%">Новий продукт</div>
        <div class="flexChild 1tableDiv" style="min-width:15%">Кількість</div>
        <div class="flexChild 1tableDiv" style="min-width:15%">Ціна</div>
        <div class="flexChild 1tableDiv" style="min-width:15%">Вартість</div>
        <div class="flexChild 1tableDiv" style="min-width:5%"></div>
    </div>
        <?php foreach ($itemModels as $i => $item): ?>
<!--        <pre>--><?php //var_dump($item) ?><!--</pre>-->

    <div data-id="<?= $i ?>" class="productContent containerFlex">
            <div class="flexChild" style="min-width:10%"><?= $item->articul ?></div>
<!--            <div class="flexChild" style="min-width:15%">--><?php //= $item->{[$i]product_id} ?><!--</div>-->
            <div class="flexChild" style="min-width:15%"><?= $form->field($item, "[$i]product_id")->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Обери продукт ...'],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ])->label(false) ?></div>
            <div class="flexChild" style="min-width:15%"><?= $item->quantity ?></div>
            <div class="flexChild" style="min-width:15%"><?= $item->price ?></div>
            <div class="flexChild" style="min-width:15%"><?= $item->quantity ?></div>
            <div class="flexChild" style="min-width:15%"><?= $item->quantity ?></div>
            <div class="flexChild" style="min-width:5%">
                <a href="#" class="nav-link delete-item" data-id="<?= $i ?>" data-item="<?= $item->id ?? '' ?>"
                   style="margin-bottom: 20px;">
                    <i class="far fa-trash-alt"></i>
                </a>
            </div>
<!--            <tr data-id="--><?php //= $i ?><!--">-->
<!--                --><?php //= $form->field($item, "[$i]id")->hiddenInput()->label(false); ?>
<!--                <td>--><?php //= $form->field($item, "[$i]articul")->textInput(['value' => $item->products->articul ?? ''])->label(false); ?><!--</td>-->
<!--                <td>-->
<!--                    --><?php //= $form->field($item, "[$i]product_id")->widget(Select2::classname(), [
//                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
//                        'options' => ['placeholder' => 'Обери продукт ...'],
//                        'pluginOptions' => [
//                            'allowClear' => true
//                        ],
//                    ])->label(false); ?>
<!--                </td>-->
<!--                <td>--><?php //= $form->field($item, "[$i]new_product")->label(false); ?><!--</td>-->
<!--                <td>--><?php //= $form->field($item, "[$i]quantity")->label(false); ?><!--</td>-->
<!--                <td>--><?php //= $form->field($item, "[$i]price")->label(false); ?><!--</td>-->
    </div>
        <?php endforeach; ?>
</div>
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
                <td><?= $form->field($item, "[$i]articul")->textInput(['value' => $item->products->articul ?? ''])->label(false); ?></td>
                <td>
                    <?= $form->field($item, "[$i]product_id")->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Обери продукт ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label(false); ?>
                </td>
                <td><?= $form->field($item, "[$i]new_product")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]quantity")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]price")->label(false); ?></td>
                <td><a href="#" class="nav-link delete-item" data-id="<?= $i ?>" data-item="<?= $item->id ?? '' ?>"
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
               value="<?= $invoiceModel->total_amount ?? 0 ?>" style="width: 22%;float: right;margin-right: 74px;">
        <div style="float: right;"><h5 style="line-height: 36px;font-weight: 600; margin-right: 15px">Разом: </h5></div>
    </div>

<?php ActiveForm::end(); ?>

<?php
$scriptCalculate = <<< JS
    $('#invoiceItems').on('input change', function() {
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
    });
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
            'url' : '/invoice/add'
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
            'url' : '/invoice/add-payment'
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
    });

    $(document).on('click', '.delete-payment', function() {
      const id = $(this).data('id');  
      const paymentId = $('#invoice-id').val(); 

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
JS;

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJs($scriptDelete, yii\web\View::POS_READY);
$this->registerJs($scriptCalculate, yii\web\View::POS_READY);
