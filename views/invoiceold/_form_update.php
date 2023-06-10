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

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, "invoice")->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, "customer_id")->dropDownList(
                    \yii\helpers\ArrayHelper::map(\common\models\Customer::find()->orderBy(['created_at' => SORT_DESC])->all(), 'id', 'name'),
                    ['prompt' => 'Select the customer name']
                ) ?>            </div>
            <div class="col-md-3">
                <?= $form->field($model, "transfer_type")->dropDownList([
                    'sale' => 'Реализация товара',
                    'income' => 'Поступление товара',
                    'transfer' => 'Перемещение',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, "store")->dropDownList([
                    'main' => 'Основной склад',
                    'additional' => 'Дополнительный склад',
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, "date")->widget(
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
                <?= $form->field($model, "product_id")->dropDownList(
                    \yii\helpers\ArrayHelper::map(\common\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                    ['prompt' => 'Select the product']
                ) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, "quantity")->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, "price")->textInput(['maxlength' => true]) ?>
            </div>
        </div>


        <div id="ajax_forms"></div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
