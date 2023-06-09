<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Продукти', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Ви впевнені щодо видалення?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'articul',
                    'name',
                    [
                        'attribute' => 'category_id',
                        'value' => function ($data) {
                            return $data->category->title;
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($data) {
                            return $data->status ? 'Активний' : 'Прихований';
                        },
                    ],
                ],
            ]) ?>

        </div>
    </section>
</div>
