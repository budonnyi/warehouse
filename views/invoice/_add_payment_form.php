<?php

use \yii\helpers\Html;

?>

<tr data-id="<?= $cnt ?>">
    <td style="display:none">
        <input type="hidden" id="payment-<?= $cnt ?>-id" value="<?= $cnt ?>" name="Payment[<?= $cnt ?>][id]">
        <input type="hidden" id="payment-<?= $cnt ?>-currency" value="uah" name="Payment[<?= $cnt ?>][currency]">
        <input type="hidden" id="payment-<?= $cnt ?>-status" value="1" name="Payment[<?= $cnt ?>][status]">
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-date" style="margin:0">
            <input type="date" id="payment-<?= $cnt ?>-date" class="form-control payment-date"
                   name="Payment[<?= $cnt ?>][date]" value="" data-number="<?= $cnt ?>"
                   min="1997-01-01" max="2030-12-31">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-description" style="margin:0">
            <input type="text" id="payment-<?= $cnt ?>-description" class="form-control payment-description"
                   name="Payment[<?= $cnt ?>][description]" data-number="<?= $cnt ?>">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-direction required" style="margin:0">
            <?= Html::dropDownList("Payment[$cnt][direction]", 'income',
                ['income' => 'Надходження', 'payment' => 'Оплата'],
                ['prompt' => '—', 'class' => 'form-control payment-direction', 'id' => 'payment-' . $cnt . '-direction', 'data-number' => $cnt]
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-category_id required" style="margin:0">
            <?= Html::dropDownList("Payment[$cnt][category_id]", 9,
                \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                ['prompt' => '—', 'class' => 'form-control payment-category-id', 'id' => 'payment-' . $cnt . '-category_id', 'data-number' => $cnt]
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-customer_id required" style="margin:0">
            <?= Html::dropDownList("Payment[$cnt][customer_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => '—', 'class' => 'form-control payment-customer-id select2bs4', 'style' => 'width:100%', 'id' => 'payment-' . $cnt . '-customer_id', 'data-number' => $cnt]
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-amount" style="margin:0">
            <input type="text" id="payment-<?= $cnt ?>-amount" class="form-control payment-amount"
                   name="Payment[<?= $cnt ?>][amount]" data-number="<?= $cnt ?>">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <a href="#" class="ef-del-btn delete-payment" data-id="<?= $cnt ?>" data-item="">
            <i class="far fa-trash-alt"></i>
        </a>
    </td>
</tr>

<script>
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
</script>