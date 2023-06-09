<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

?>
<div class="container">

    <?php $form = \yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoice')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
        ['prompt' => 'Select the product']
    ) ?>

    <?= $form->field($model, 'customer_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Customer::find()->orderBy(['created_at' => SORT_DESC])->all(), 'id', 'name'),
        ['prompt' => 'Select the customer name']
    ) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transfer_type')->dropDownList([
        'income' => 'Поступление товара',
        'sale' => 'Реализация товара',
        'transfer' => 'Перемещение',
    ]) ?>

    <?= $form->field($model, 'date')->widget(
        \kartik\date\DatePicker::className(), [
        'value' => date('Y-m-d', time()),
        'options' => ['placeholder' => 'Выбери дату ...'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]);?>

    <?= $form->field($model, 'store')->dropDownList([
        'main' => 'Основной склад',
        'additional' => 'Дополнительный склад',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
