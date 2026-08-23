<?php

use \yii\helpers\Html;

?>

<tr data-id="<?= $cnt ?>">

    <input type="hidden" id="invoiceitem-<?= $cnt ?>-id" class="form-control" value="<?= $cnt ?>" name="InvoiceItem[<?= $cnt ?>][id]">
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-articul">
            <input type="text" id="invoiceitem-<?= $cnt ?>-articul" class="form-control"
                   name="InvoiceItem[<?= $cnt ?>][articul]">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-product_id required">
            <?= Html::dropDownList("InvoiceItem[$cnt][product_id]", null,
                \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Select the product', 'class' => 'form-control']
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
            <input type="text" id="invoiceitem-<?= $cnt ?>-quantity" class="form-control"
                   name="InvoiceItem[<?= $cnt ?>][quantity]" aria-required="true">
            <div class="help-block"></div>
        </div>
    </td>
    <td>
        <div class="form-group field-invoiceitem-<?= $cnt ?>-price">
            <input type="text" id="invoiceitem-<?= $cnt ?>-price" class="form-control"
                   name="InvoiceItem[<?= $cnt ?>][price]">
            <div class="help-block"></div>
        </div>
    </td>
    <td><a href="#" class="nav-link delete-item" data-id="<?= $cnt ?>" style="margin-bottom: 20px;">
            <i class="far fa-trash-alt"></i>
        </a></td>
</tr>