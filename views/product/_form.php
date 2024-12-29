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

<?= $form->field($model, 'name_invoice')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

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

<?php if (!empty($model->attachments) && !$model->isNewRecord && !empty($model->id)): ?>
    <?php
    $img = [];
    $json = [];
    foreach ($model->attachments as $attachment) {
        $root = '/products/' . $model->id . '/';
        $img[] = $root . $attachment->filename;

        $type = pathinfo($attachment->filename, PATHINFO_EXTENSION);
        $json[] = [
            'type' => in_array($type, ['jpg', 'jpeg']) ? 'image' : $type,
            'caption' => $attachment->filename,
            \yii\helpers\Url::to(['/attachment/delete-upload']),
            'key' => 'filename ' . $attachment->id,
        ];
    }
    ?>

    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
        'options' => ['accept' => '', 'multiple' => true],
        'pluginOptions' => [
            'showDownload' => true,
            'initialPreviewAsData' => true,
            'showCancel' => false,
            'showPreview' => true,
            'initialPreview' => $img,
            'initialPreviewConfig' => $json,
            'previewSettings' => [
                'image' => ['width' => "auto", 'height' => "auto", 'max-width' => "100%", 'max-height' => "100%"],
            ],
            'previewFileType' => 'any',
            'uploadAsync' => true,
            'deleteUrl' => \yii\helpers\Url::to(['/attachment/delete-upload']),
            'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
            'uploadExtraData' => [
                'entity_id' => $model->id,
                'entity_type' => \app\models\Attachment::PRODUCT
            ],
        ]
    ]) ?>
<?php elseif (!empty($model->id)) : ?>
    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::classname(), [
        'options' => ['accept' => '', 'multiple' => true],
        'pluginOptions' => [
            'showCancel' => false,
            'previewFileType' => 'any',
            'uploadUrl' => \yii\helpers\Url::to(['/attachment/files-upload']),
            'uploadExtraData' => [
                'entity_id' => $model->id,
                'entity_type' => \app\models\Attachment::PRODUCT
            ],
        ],
    ]); ?>
<?php endif; ?>
