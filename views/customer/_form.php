<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3">
            <?php $statusArr = Yii::$app->params['customerTypes'] ?>
            <?= $form->field($model, "type")->dropDownList(
                $statusArr,
                ['prompt' => 'Оберіть тип']
            ) ?>
        </div>
        <div class="col-md-3"><?= $form->field($model, 'status')->dropDownList([
                1 => 'Активний',
                0 => 'Прихований'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php if (!empty($model->attachments) && !$model->isNewRecord && !empty($model->id)): ?>
    <?php
    $img = [];
    $json = [];
    foreach ($model->attachments as $attachment) {
        $root = '/customers/' . $model->id . '/';
        $img[] = $root . $attachment->filename;

        $type = pathinfo($attachment->filename, PATHINFO_EXTENSION);
        $json[] = [
            'type' => in_array($type, ['jpg', 'jpeg']) ? 'image' : $type,
            'caption' => $attachment->filename,
            'width' => '120px',
            \yii\helpers\Url::to(['/attachment/delete-upload']),
            'key' => 'filename ' . $attachment->id,
        ];
    }
    ?>

    <?= $form->field(new \app\models\Attachment(), 'filename')->widget(\kartik\file\FileInput::className(), [
        'options' => ['accept' => '', 'multiple' => true],
        'pluginOptions' => [
            'initialPreviewFileType' => 'image',
            'overwriteInitial' => false,
//                'allowedFileExtensions' => ['jpg', 'jpeg'],

            'allowedPreviewTypes' => 'image',
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
                'entity_type' => \app\models\Attachment::CUSTOMER
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
                'entity_type' => \app\models\Attachment::CUSTOMER
            ],
        ],
    ]); ?>
<?php endif; ?>
