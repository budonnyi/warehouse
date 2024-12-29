<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Payment $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Платежі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
    </div>
    <div class="card-header">
        <div class="row">
            <div class="col-md-3 col-6" style="margin-bottom: 10px">
                <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-block btn-outline-primary btn-sm']) ?>
            </div>
            <div class="col-md-3 col-6">
                <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-block btn-outline-danger btn-sm',
                    'data' => [
                        'confirm' => 'Ви впевнені щодо видалення цього документу?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="direct-chat-messages" style="height: 100%;">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'date',
                    'amount',
                    'invoice_id',
                    'category_id',
                    [
                        'attribute' => 'status',
                        'value' => function ($data) {
                            return $data->status ? 'Активний' : 'Прихований';
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>