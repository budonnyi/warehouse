<?php

use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категорії товарів';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a('Нова категорія', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'id' => 'theDatatable',
                    'class' => 'table table-hover text-nowrap'
                ],
                'columns' => [
//                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'title',
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'filter' => array('1' => 'Активний', '0' => 'Прихований'),
                        'headerOptions' => ['style' => 'text-align:center'],
                        'value' => function ($data) {
                            return $data->status == 1 ? 'Активний' : 'Прихований';
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Category $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>
    </section>
</div>