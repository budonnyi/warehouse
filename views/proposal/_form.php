<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Proposal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="proposal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, "date")->widget(
        DatePicker::className(), [
        'value' => date('Y-m-d', time()),
        'options' => ['placeholder' => 'Обери дату ...'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, "customer_id")->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                ['prompt' => 'Оберіть контрагента']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'newCustomer')->textInput() ?>
        </div>
    </div>

    <?= $form->field($model, 'person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'inline' => false,
        ]),
    ]); ?>

    <table id="invoiceItems" style="width:100%">
        <thead>
        <tr>
            <!--            <th style="width: 10%">ID</th>-->
            <th style="width: 10%">Артикул</th>
            <th style="width: 20%">Продукт</th>
            <th style="width: 20%">Новий продукт</th>
            <th style="width: 15%">Кількість</th>
            <th style="width: 20%">Ціна</th>
            <th style="width: 5%"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($itemModels as $i => $item): ?>
            <tr data-id="<?= $i ?>">
                <?= $form->field($item, "[$i]id")->hiddenInput()->label(false); ?>
                <td><?= $form->field($item, "[$i]articul")->textInput(['value' => $item->products->articul ?? ''])->label(false); ?></td>
                <td><?= $form->field($item, "[$i]product_id")->dropDownList(
                        \yii\helpers\ArrayHelper::map(\app\models\Product::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                        ['prompt' => 'Select the product']
                    )->label(false); ?></td>
                <td><?= $form->field($item, "[$i]new_product")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]quantity")->label(false); ?></td>
                <td><?= $form->field($item, "[$i]price")->label(false); ?></td>
                <td><a href="#" class="nav-link delete-item" data-id="<?= $i ?>" data-item="<?= $item->id ?? '' ?>"
                       style="margin-bottom: 20px;">
                        <i class="far fa-trash-alt"></i>
                    </a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::a('Додати продукт', 'javascript:void(0)',
            ['class' => 'btn btn-info btn-add-product', 'data-count' => $count]) ?>
    </div>



    <?= $form->field($model, 'conditions')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'inline' => false,
        ]),
    ]); ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'inline' => false,
        ]),
    ]); ?>

    <?= $form->field($model, 'from')->dropDownList(['dmytro' => 'Дмитро', 'yana' => 'Яна']) ?>

    <?= $form->field($model, 'status')->dropDownList([
        '2' => 'Відправлено',
        '1' => 'Очікування',
        '0' => 'Відмінено'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
    $('.btn-add-product').on('click', function() {
      var ths = $(this);  
      var cnt = ths.attr('data-count');

      $.ajax({
            'dataType' : 'html',
            'data': {'cnt': cnt},
            'success' : function(data) {
                $('#invoiceItems').find('tbody').append(data);
                // $('#ajax_forms').append(data);
                ths.attr('data-count', parseInt(cnt)+1);
            },
            'type' : 'post',
            'url' : '/invoice/add'
        });
    })
JS;

$scriptDelete = <<< JS
    $(document).on('click', '.delete-item', function() {
      const id = $(this).data('id');  
      const invoiceId = $('#invoice-id').val(); 

      $('#invoiceItems').find('tr[data-id=' + id + ']').remove();

      if (id > 0) 
          $.ajax({
                'data': {id, invoiceId},
                'success' : function() {
                    console.log('Deleted');
                },
                'type' : 'post',
                'url' : '/invoice/erase'
          });
    })
JS;

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJs($scriptDelete, yii\web\View::POS_READY);
