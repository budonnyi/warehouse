<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Attachment;
use kartik\file\FileInput;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Attachment */
/* @var $form yii\widgets\ActiveForm */
$url = Yii::$app->urlManager->baseUrl;
?>

<div class="Category-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?php if (!$model->isNewRecord): ?>
        <?php
        $img = [];
        $json = [];
        if (!empty($model->filename)) {

            $img[] = Html::a($model->filename, $url . '/files/' . $model->filename);

            $json[] = [
                'caption' => $model->filename, Url::to(['/attachment/delete-upload']),
                'key' => 'filename ' . $model->id,
            ];
        }
        ?>

        <?= $form->field($model, 'filename')->widget(FileInput::className(), [
            'options' => ['accept' => '', 'multiple' => true],
            'pluginOptions' => [
//                'showRemove' => false,
//                'showUpload' => false,
                'showCancel' => false,
//                'overwriteInitial' => false,
                'initialPreviewConfig' => $json,
//                'previewFileType' => 'image',
                'previewFileType' => 'any',
                'initialPreview' => $img,
                'uploadAsync' => true,
                'maxFileSize' => 3 * 1024 * 1024,
                'deleteUrl' => Url::to(['/attachment/delete-upload']),
                'uploadUrl' => Url::to(['/attachment/files-upload']),
                'uploadExtraData' => [
                    'invoice_id' => 383,
                ],
//                'allowedExtensions' => ['jpg','png','jpeg'],
            ]
        ]) ?>
    <?php else : ?>
        <?= $form->field($model, 'filename')->widget(FileInput::classname(), [
            'options' => ['accept' => '', 'multiple' => true],
            'pluginOptions' => [
                'showCancel' => false,
//                'showUpload' => false,
                'previewFileType' => 'any',
                'uploadUrl' => Url::to(['/attachment/files-upload']),
                'uploadExtraData' => [
                    'invoice_id' => 383,
                ],
            ],
        ]); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<strong>Create</strong>') : Yii::t('app', '<strong>Update</strong>'), ['class' => $model->isNewRecord ? 'btn btn-success btn-slideright btn-md rounded' : 'btn btn-primary btn-slideright btn-md rounded']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>