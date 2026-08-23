<?php

use \yii\helpers\Html;
//use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Product;

$productArr = Product::find()->orderBy(['name' => SORT_ASC])->all();

?>

<tr data-id="<?= $cnt ?>">
    <td style="display:none">
        <input type="hidden" id="invoiceitem-<?= $cnt ?>-id" value="<?= $cnt ?>" name="InvoiceItem[<?= $cnt ?>][id]">
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-articul" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-articul" class="form-control articul-field"
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
            <input type="text" id="invoiceitem-<?= $cnt ?>-quantity" class="form-control quantity-field"
                   name="InvoiceItem[<?= $cnt ?>][quantity]" aria-required="true">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-price" style="margin:0">
            <input type="text" id="invoiceitem-<?= $cnt ?>-price" class="form-control price-field"
                   name="InvoiceItem[<?= $cnt ?>][price]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <a href="#" class="ef-del-btn delete-item" data-id="<?= $cnt ?>">
            <i class="far fa-trash-alt"></i>
        </a>
    </td>
</tr>

<script>
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
</script>