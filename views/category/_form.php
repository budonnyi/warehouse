<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

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