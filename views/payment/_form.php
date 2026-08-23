<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Payment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, "date")->widget(
            DatePicker::className(), [
            'value' => date('Y-m-d', time()),
            'options' => ['placeholder' => 'Выбери дату ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, "direction")->dropDownList(
            ['payment' => 'Оплата', 'income' => 'Надходженя']
        ) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'currency')->dropDownList(['uah' => 'грн', 'sek' => 'SEK', 'eur' => 'EUR']/*, ['prompt' => 'Валюта']*/) ?>
    </div>

</div>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'category_id')->dropDownList(
            \yii\helpers\ArrayHelper::map(\app\models\PaymentCategory::find()->all(), 'id', 'title')
        ) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, "customer_id")->dropDownList(
            \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
            ['prompt' => 'Виберіть отримувача']
        ) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'row' => 4]) ?>
    </div>

</div>


<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, "invoice_id")->dropDownList(
            \yii\helpers\ArrayHelper::map(\app\models\Invoice::find()->orderBy(['date' => SORT_DESC])->all(), 'id', 'invoice'),
            ['prompt' => 'Виберіть інвойс', 'id' => 'invoicesSelect']
        ) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'status')->dropDownList(
            [
                1 => 'Активний',
                0 => 'Прихований'
            ]
        ) ?>
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
        $root = '/payments/' . $model->id . '/';
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
                'entity_type' => \app\models\Attachment::PAYMENT
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
                'entity_type' => \app\models\Attachment::PAYMENT
            ],
        ],
    ]); ?>
<?php endif; ?>

<script>
    $(document).ready(function () {
        // const direction = $('#payment-direction').val();
        // console.log(direction)
        // getSelect(direction);
        //
        // $(document).on('change', '#payment-direction', function () {
        //     const direction = $(this).val();
        //     getSelect(direction);
        // })

        // function getSelect(direction) {
        //     console.log(direction)
        //     $.ajax({
        //         'dataType': 'json',
        //         'data': {direction},
        //         'success': function (data) {
        //             const entries = Object.entries(data);
        //             var optionCollection = '<option value="">Виберіть інвойс</option>';
        //             $.each(entries, function (key, value) {
        //                 optionCollection += `<option value="${$(this)[0]}">${$(this)[1]}</option>`;
        //             })
        //             $('#invoicesSelect').empty().append(optionCollection);
        //         },
        //         'type': 'post',
        //         'url': '/payment/invoices'
        //     });
        // }
    });
</script>
