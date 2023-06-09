<?php

use \yii\helpers\Html;

?>

<div class="form-group field-product-0-title">

    <div class="row">
        <div class="col-md-4">
            <?= Html::label(
                $model->getAttributeLabel('product_id'),
                "product-$cnt-product_id",
                ['class' => 'control-label'])
            ?>
            <?= Html::dropDownList("Invoice[$cnt][product_id]", null,
                \yii\helpers\ArrayHelper::map(\common\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Select the product', 'class' => 'form-control']
            ) ?>
            <div class="help-block"></div>
        </div>
        <div class="col-md-4">
            <?= Html::label(
                $model->getAttributeLabel('quantity'),
                "product-$cnt-quantity",
                ['class' => 'control-label'])
            ?>
            <?= Html::textInput(
                "Invoice[$cnt][quantity]",
                '',
                ['class' => 'form-control', 'id' => "product-$cnt-quantity"])
            ?>
            <div class="help-block"></div>
        </div>
        <div class="col-md-4">
            <?= Html::label(
                $model->getAttributeLabel('price'),
                "product-$cnt-price",
                ['class' => 'control-label'])
            ?>
            <?= Html::textInput(
                "Invoice[$cnt][price]",
                '',
                ['class' => 'form-control', 'id' => "product-$cnt-price"])
            ?>
            <div class="help-block"></div>
        </div>
    </div>






</div>
