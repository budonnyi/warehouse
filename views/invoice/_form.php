<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Invoice $invoiceModel */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, 'invoice')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "date")->widget(
                DatePicker::className(), [
                'value' => date('Y-m-d', time()),
                'options' => ['placeholder' => 'Выбери дату ...'],
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
                'options' => ['placeholder' => 'Выбери дату ...'],
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
                'options' => ['placeholder' => 'Выбери дату ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($invoiceModel, "customer_id")->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Виберіть контрагента']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($invoiceModel, 'newCustomer')->textInput() ?>
        </div>
    </div>

    <table id="invoiceItems" style="width:100%">
        <thead>
        <tr>
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
                <td><?= $form->field($item, "[$i]articul")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]product_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Select the product']
                    )->label(false); ?></td>
                <td><?= $form->field($item, "[$i]new_product")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]quantity")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]price")->label(false); ?></td>
                <td><a href="#" class="nav-link delete-item" data-id="<?= $i ?>" style="margin-bottom: 20px;">
                        <i class="far fa-trash-alt"></i>
                    </a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::a('Додати продукт', 'javascript:void(0)',
            ['class' => 'btn btn-info btn-add-product', 'data-count' => $count]) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php
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
    })
JS;

$scriptDelete = <<< JS
    $(document).on('click', '.delete-item', function() {
      const id = $(this).data('id');  
      console.log(id)
      $('#invoiceItems').find('tr[data-id=' + id + ']').remove();

    })
JS;

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJs($scriptDelete, yii\web\View::POS_READY);
