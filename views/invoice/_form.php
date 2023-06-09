<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
$prompt = [];
?>

    <div class="invoice-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php foreach ($model as $index => $item) : ?>
<!--        <pre>-->
<?php //var_dump($model); die; ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($item, "[$index]invoice")->textInput(['maxlength' => true]) ?>
            </div>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($item, "[$index]newCustomer")->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($item, "[$index]customer_id")->dropDownList(
                    \yii\helpers\ArrayHelper::map(\common\models\Customer::find()->orderBy(['created_at' => SORT_DESC])->all(), 'id', 'name'),
                    ['prompt' => 'Select the customer name']
                ) ?>            </div>
            <div class="col-md-3">
                <?= $form->field($item, "[$index]transfer_type")->dropDownList([
                    'sale' => 'Реализация товара',
                    'income' => 'Поступление товара',
                    'transfer' => 'Перемещение',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($item, "[$index]store")->dropDownList([
                    'main' => 'Основной склад',
                    'additional' => 'Дополнительный склад',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($item, "[$index]date")->widget(
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
            <div class="col-md-4">
                <?= $form->field($item, "[$index]product_id")->dropDownList(
                    \yii\helpers\ArrayHelper::map(\common\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                    ['prompt' => 'Select the product']
                ) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($item, "[$index]quantity")->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($item, "[$index]price")->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?php endforeach; ?>

        <div id="ajax_forms"></div>

        <div class="form-group">
            <?= Html::a('Добавить продукт', 'javascript:void(0)',
                ['class' => 'btn btn-info btn-add-product', 'data-count' => $count]) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$script = <<< JS
    $('.btn-add-product').on('click', function() {
      var ths = $(this);  
      var cnt = ths.attr('data-count');

      $.ajax({
            'dataType' : 'html',
            'data': {'cnt': cnt},
            'success' : function(data) {
                $('#ajax_forms').append(data);
                ths.attr('data-count', parseInt(cnt)+1);
            },
            'type' : 'post',
            'url' : '/admin/invoice/add'
        });
    })
JS;
$this->registerJs($script, yii\web\View::POS_READY);
