<?php

use \yii\helpers\Html;

?>

<tr data-id="<?= $cnt ?>">

    <input type="hidden" id="payment-<?= $cnt ?>-id" class="form-control" value="<?= $cnt ?>" name="Payment[<?= $cnt ?>][id]">
    <input type="hidden" id="payment-<?= $cnt ?>-direction" class="form-control" value="payment" name="Payment[<?= $cnt ?>][direction]">
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
        <div class="form-group field-payment-<?= $cnt ?>-category_id required">
            <?= Html::dropDownList("Payment[$cnt][category_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                ['prompt' => 'Select the product', 'class' => 'form-control']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-customer required">
            <?= Html::dropDownList("Payment[$cnt][customer_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Контрагент', 'class' => 'form-control select2bs4', 'style'=>"width: 100%;"]
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-currency required">
            <?= Html::dropDownList("Payment[$cnt][currency]", null,
                ['uah' => 'ГРН', 'sek' => 'SEK'],
                ['prompt' => 'Валюта', 'class' => 'form-control']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-price">
            <input type="text" id="payment-<?= $cnt ?>-price" class="form-control"
                   name="Payment[<?= $cnt ?>][amount]">
            <div class="help-block"></div>
        </div>
    </td>
    <td><a href="#" class="nav-link delete-payment" data-id="<?= $cnt ?>" style="margin-bottom: 20px;">
            <i class="far fa-trash-alt"></i>
        </a></td>
</tr>

<script>
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
</script>