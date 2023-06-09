<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'articul')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'category_id')->dropDownList(
    \yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'title')
) ?>

<?= $form->field($model, 'status')->dropDownList(
    [
        1 => 'Активний',
        0 => 'Прихований'
    ]
) ?>

<div class="form-group">
    <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>