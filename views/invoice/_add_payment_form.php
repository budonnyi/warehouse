<?php

use \yii\helpers\Html;

?>

<tr data-id="<?= $cnt ?>">

    <input type="hidden" id="payment-<?= $cnt ?>-id" class="form-control" value="<?= $cnt ?>" name="Payment[<?= $cnt ?>][id]">
    <input type="hidden" id="payment-<?= $cnt ?>-currency" class="form-control" value="uah" name="Payment[<?= $cnt ?>][currency]">
<!--    <input type="hidden" id="payment---><?php //= $cnt ?><!---direction" class="form-control" value="income" name="Payment[--><?php //= $cnt ?><!--][direction]">-->
    <input type="hidden" id="payment-<?= $cnt ?>-status" class="form-control" value="1" name="Payment[<?= $cnt ?>][status]">

    <td>
        <div class="form-group field-payment-<?= $cnt ?>-date">
            <input type="date" id="payment-<?= $cnt ?>-date" class="form-control datepicker"
                   name="Payment[<?= $cnt ?>][date]" placeholder="dd-mm-yyyy" value=""
                   min="1997-01-01" max="2030-12-31" required>
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-description">
            <input type="text" id="payment-<?= $cnt ?>-description" class="form-control"
                   name="Payment[<?= $cnt ?>][description]" required>
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-direction required">
            <?= Html::dropDownList("Payment[$cnt][direction]", 'income',
                ['income' => 'Надходженя', 'payment' => 'Оплата'],
                ['prompt' => 'Select the direction', 'class' => 'form-control', 'id' => 'payment-' . $cnt . '-direction']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-category_id required">
            <?= Html::dropDownList("Payment[$cnt][category_id]", 9,
                \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                ['prompt' => 'Select the product', 'class' => 'form-control', 'id' => 'payment-' . $cnt . '-category_id']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-customer_id required">
            <?= Html::dropDownList("Payment[$cnt][customer_id]", 9,
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Select the customer', 'class' => 'form-control select2bs4', 'style'=>"width: 100%;", 'id' => 'payment-' . $cnt . '-customer_id']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-amount">
            <input type="text" id="payment-<?= $cnt ?>-amount" class="form-control"
                   name="Payment[<?= $cnt ?>][amount]" required>
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div style="margin-left: 17px;
    margin-bottom: 23px;">
        <span class="delete-payment" data-id="<?= $cnt ?>" style="">
            <i class="far fa-trash-alt"></i>
        </span>
        <span class="do-payment" data-id="<?= $cnt ?>" style="margin-left: 10px">
            <i class="fab fa-cc-apple-pay"></i>
        </span>
        </div>
    </td>
</tr>

<script>
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
</script>