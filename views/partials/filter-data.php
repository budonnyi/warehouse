<?php

use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;

$startDate = date('Y-m-d', strtotime(Yii::$app->session->get('start-date')));
$endDate = date('Y-m-d', strtotime(Yii::$app->session->get('end-date')));
$selectedDate = $startDate . ' - ' . $endDate;

?>

<div class="filters">

    <?php $form = ActiveForm::begin([
        'action' => ['store'],
        'method' => 'post',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <!-- блок выбора даты -->
    <div class="box">
        <h6>Период</h6>
        <?php
        $modelSelect->date = $selectedDate;
        echo $form->field($modelSelect, 'date', [
            'options' => ['class' => 'drp-container form-group']])
            ->widget(DateRangePicker::classname(), [
                'convertFormat' => true,
//                    'value' => $selectedDate,
                'pluginOptions' => [
                    'locale' => [
                        'format' => 'Y-m-d',
                        'separator' => ' - ',
                    ],
                    'opens' => 'right',
                ],

            ])
            ->label(false);
        ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Подобрать', ['class' => 'btn btn-primary'/*, 'style' => 'visibility: hidden'*/]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<!--<script>-->
<!--    $('select#region_id').change(function () {-->
<!--        $("#w0").submit()-->
<!--    });-->
<!---->
<!--    $('select#studio_id').change(function () {-->
<!--        $("#w0").submit()-->
<!--    });-->
<!---->
<!--    $('input#select-date').change(function () {-->
<!--        // alert('343121');-->
<!--        $("#w0").submit()-->
<!--    });-->
<!--</script>-->

<?php
$script = <<< JS
    $('select#region_id').change(function () {
        $("#w0").submit()
    });

    $('select#studio_id').change(function () {
        $("#w0").submit()
    });

    $('input#select-date').change(function () {
        // alert('343121');
        $("#w0").submit()
    });
JS;
$this->registerJs($script, yii\web\View::POS_READY);
