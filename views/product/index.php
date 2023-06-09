<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товари';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a('Новий товар', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <!--            --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                    'articul',
                    'name',
                    [
                        'attribute' => 'category_id',
                        'label' => 'Категорія',
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\Category::find()->orderBy(['title' => SORT_DESC])->all(), 'id', 'title'),
                        'headerOptions' => ['style' => 'text-align:center'],
                        'value' => function ($data) {
                            return $data->category->title;
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'filter' => array('1' => 'Активний', '0' => 'Прихований'),
                        'headerOptions' => ['style' => 'text-align:center'],
                        'value' => function ($data) {
                            return $data->status == 1 ? 'Активний' : 'Прихований';
                        },
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </section>
</div>
