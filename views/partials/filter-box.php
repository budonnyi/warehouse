<?php

use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;

?>

<div class="filters">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <!-- блок фильтров -->
    <div class="box">
        <h6>Склад</h6>
        <?= $form
            ->field($modelSelect, 'store')
            ->dropDownList($storeFilter, [
                'prompt' => 'Все',
                'value' => $selectedStore,
                'id' => 'region_id',
                'class' => 'form-control input-sm'
            ])->label(false);
        ?>
    </div>

    <div class="box">
        <h6>Направление</h6>
        <?= $form
            ->field($modelSelect, 'transfer_type')
            ->dropDownList($transferFilter, [
                'prompt' => 'Все',
//                    'income' => 'Покупка',
//                    'sale' => 'Продажа',
                'value' => $selectedTransferType,
                'class' => 'form-control input-sm',
                'id' => 'way_id'
            ])->label(false);
        ?>
    </div>

    <div class="box">
        <h6>Товар</h6>
        <?= $form
            ->field($modelSelect, 'product')
            ->dropDownList($productFilter, [
                'prompt' => 'Все',
                'value' => $selectedProduct,
                'id' => 'studio_id'
            ])
            ->label(false);
        ?>
    </div>

    <div class="box">
        <h6>Клиент</h6>
        <?= $form
            ->field($modelSelect, 'customer')
            ->dropDownList($customerFilter, [
                'prompt' => 'Все',
                'value' => $selectedCustomer,
                'id' => 'region_id',
                'class' => 'form-control input-sm'
            ])
            ->label(false);
        ?>
    </div>

    <!-- блок выбора даты -->
    <div class="box">
        <h6>Период</h6>
        <?php
        $modelSelect->date = $selectedDate;
        echo $form->field($modelSelect, 'date', [
            'options' => ['class' => 'drp-container form-group']])
            ->widget(DateRangePicker::classname(), [
                'convertFormat' => true,
//                    'value' => $selectedDate,
                'pluginOptions' => [
                    'locale' => [
                        'format' => 'Y-m-d',
                        'separator' => ' - ',
                    ],
                    'opens' => 'right',
                ],

            ])
            ->label(false);
        ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Подобрать', ['class' => 'btn btn-primary'/*, 'style' => 'visibility: hidden'*/]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    $('select#region_id').change(function () {
        $("#w0").submit()
    });

    $('select#studio_id').change(function () {
        $("#w0").submit()
    });

    $('input#select-date').change(function () {
        alert('343121');
        $("#w0").submit()
    });
</script>

