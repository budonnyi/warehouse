<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CustomersStaff $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="customers-staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, "customer_id")->dropDownList(
                    \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                    ['prompt' => 'Select the customer', 'class' => 'form-control payment-customer-id select2bs4',
                        'style' => "width: 100%;"]
                ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList([
                1 => 'Активний',
                0 => 'Прихований'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
