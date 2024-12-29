<?php

use \yii\helpers\Html;

?>

<tr data-id="<?= $cnt ?>" class="<?= $service ? 'service' : '' ?>">

    <input type="hidden" id="invoiceitem-<?= $cnt ?>-id" class="form-control" value="<?= $cnt ?>" name="InvoiceItem[<?= $cnt ?>][id]">
    <input type="hidden" id="invoiceitem-<?= $cnt ?>-service" class="form-control" value="<?= $service ?>" name="InvoiceItem[<?= $cnt ?>][service]">
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-articul">
            <input type="text" id="invoiceitem-<?= $cnt ?>-articul" class="form-control articul"
                   name="InvoiceItem[<?= $cnt ?>][articul]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-product_id required">
            <?= Html::dropDownList("InvoiceItem[$cnt][product_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Select the product', 'class' => 'form-control product-id select2bs4', 'style'=>"width: 100%;"]
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-new_product">
            <input type="text" id="invoiceitem-<?= $cnt ?>-new_product" class="form-control"
                   name="InvoiceItem[<?= $cnt ?>][new_product]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-quantity required">
            <input type="text" id="invoiceitem-<?= $cnt ?>-quantity" class="form-control quantity"
                   name="InvoiceItem[<?= $cnt ?>][quantity]" aria-required="true">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-price_sek">
            <input type="text" id="invoiceitem-<?= $cnt ?>-price_sek" class="form-control price_sek"
                   name="InvoiceItem[<?= $cnt ?>][price_sek]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-surcharge_sek">
            <input type="text" id="invoiceitem-<?= $cnt ?>-surcharge_sek" class="form-control surcharge_sek"
                   name="InvoiceItem[<?= $cnt ?>][surcharge_sek]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-total_sek">
            <input type="text" id="invoiceitem-<?= $cnt ?>-total_sek" class="form-control total_sek"
                   name="InvoiceItem[<?= $cnt ?>][total_sek]">
            <div class="help-block"></div>
        </div>
    </td>
    <td><a href="#" class="nav-link delete-item" data-id="<?= $cnt ?>" style="margin-bottom: 20px;">
            <i class="far fa-trash-alt"></i>
        </a></td>
</tr>

<script>
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
</script>