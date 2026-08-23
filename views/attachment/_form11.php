<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Attachment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="attachment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'invoice_id')->textInput() ?>

<!--    --><?php //= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->widget(\kartik\file\FileInput::className(),[
        'options' => ['accept' => ''],
        'pluginOptions' => [
            'showRemove'=> false,
            'showUpload' => false,
            'showCancel' => false,
            'overwriteInitial' => false,
            'initialPreviewConfig' => $json,
            'previewFileType' => 'image',
            'initialPreview' => $img,
            'uploadAsync'=> true,
            'maxFileSize' => 3*1024*1024,
            'deleteUrl' => Url::to(['/file/delete-upload']),
            'allowedExtensions' => ['jpg','png','jpeg'],
        ]
    ])?>

    <?= $form->field($model, 'status')->textInput() ?>

<!--    --><?php //= $form->field($model, 'created_at')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
