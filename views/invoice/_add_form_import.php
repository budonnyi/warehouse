<?php

use yii\helpers\Html;
use app\models\Product;

$productArr = Product::find()->orderBy(['name' => SORT_ASC])->all();

?>
<tr data-id="<?= $cnt ?>" class="<?= $service ? 'service' : '' ?>">
    <td style="display:none">
        <input type="hidden" id="invoiceitem-<?= $cnt ?>-id"      value="<?= $cnt ?>"    name="InvoiceItem[<?= $cnt ?>][id]">
        <input type="hidden" id="invoiceitem-<?= $cnt ?>-service"  value="<?= $service ?>" name="InvoiceItem[<?= $cnt ?>][service]">
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-articul" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-articul" class="form-control articul"
                   name="InvoiceItem[<?= $cnt ?>][articul]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-product_id required" style="margin:0">
            <?= Html::dropDownList("InvoiceItem[$cnt][product_id]", null,
                \yii\helpers\ArrayHelper::map($productArr, 'id', 'name'),
                ['prompt' => '— оберіть —', 'class' => 'form-control product-id select2bs4', 'style' => 'width:100%']
            ) ?>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-new_product" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-new_product" class="form-control"
                   name="InvoiceItem[<?= $cnt ?>][new_product]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-quantity required" style="margin:0">
            <input type="number" min="0" step="1" id="invoiceitem-<?= $cnt ?>-quantity" class="form-control quantity"
                   name="InvoiceItem[<?= $cnt ?>][quantity]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-price_sek" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-price_sek" class="form-control price_sek"
                   name="InvoiceItem[<?= $cnt ?>][price_sek]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-surcharge_sek" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-surcharge_sek" class="form-control surcharge_sek"
                   name="InvoiceItem[<?= $cnt ?>][surcharge_sek]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-total_sek" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-total_sek" class="form-control total_sek"
                   name="InvoiceItem[<?= $cnt ?>][total_sek]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <a href="#" class="ef-del-btn delete-item" data-id="<?= $cnt ?>" data-item="">
            <i class="far fa-trash-alt"></i>
        </a>
    </td>
</tr>
<script>$('.select2bs4').select2({ theme: 'bootstrap4' });</script>
