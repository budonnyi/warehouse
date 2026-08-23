<?php

use yii\helpers\Html;

?>
<tr data-id="<?= $cnt ?>">
    <td style="display:none">
        <input type="hidden" id="payment-<?= $cnt ?>-id"        value="<?= $cnt ?>" name="Payment[<?= $cnt ?>][id]">
        <input type="hidden" id="payment-<?= $cnt ?>-direction"  value="payment"     name="Payment[<?= $cnt ?>][direction]">
        <input type="hidden" id="payment-<?= $cnt ?>-status"     value="1"           name="Payment[<?= $cnt ?>][status]">
        <input type="hidden" id="payment-<?= $cnt ?>-currency"   value="uah"         name="Payment[<?= $cnt ?>][currency]">
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-date" style="margin:0">
            <input type="date" id="payment-<?= $cnt ?>-date" class="form-control"
                   name="Payment[<?= $cnt ?>][date]" value=""
                   min="1997-01-01" max="2030-12-31">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-description" style="margin:0">
            <input type="text" id="payment-<?= $cnt ?>-description" class="form-control"
                   name="Payment[<?= $cnt ?>][description]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-category_id" style="margin:0">
            <?= Html::dropDownList("Payment[$cnt][category_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title'),
                ['prompt' => '—', 'class' => 'form-control', 'id' => 'payment-' . $cnt . '-category_id']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-customer_id" style="margin:0">
            <?= Html::dropDownList("Payment[$cnt][customer_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => '—', 'class' => 'form-control select2bs4', 'style' => 'width:100%', 'id' => 'payment-' . $cnt . '-customer_id']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-payment-<?= $cnt ?>-amount" style="margin:0">
            <input type="text" id="payment-<?= $cnt ?>-amount" class="form-control"
                   name="Payment[<?= $cnt ?>][amount]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <a href="#" class="ef-del-btn delete-payment" data-id="<?= $cnt ?>" data-item="">
            <i class="far fa-trash-alt"></i>
        </a>
    </td>
</tr>
<script>$('.select2bs4').select2({ theme: 'bootstrap4' });</script>
