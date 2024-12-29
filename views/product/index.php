<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товари';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <?= Html::a('Новий товар', ['create'], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="direct-chat-messages" style="height: 100%;">

                    <?php Pjax::begin(); ?>
                    <!--            --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'id' => 'theDatatable',
                            'class' => 'table table-hover text-nowrap'
                        ],
                        'rowOptions' => function ($model) {
                            if ($model->status != 1) {
                                return ['style' => 'background-color: #ddd; color: #777'];
                            }
                        },
                        'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],
//                            'id',
//                            'articul',
                            'name',
//                            'name_invoice',
                            'price',
                            [
                                'attribute' => 'category_id',
                                'label' => 'Категорія',
                                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Category::find()->orderBy(['title' => SORT_DESC])->all(), 'id', 'title'),
                                'headerOptions' => ['style' => 'text-align:center'],
                                'value' => function ($data) {
                                    return $data->category->title ?? '-';
                                },
                            ],
//                            [
//                                'attribute' => 'status',
//                                'label' => 'Статус',
//                                'filter' => array('1' => 'Активний', '0' => 'Прихований'),
//                                'headerOptions' => ['style' => 'text-align:center'],
//                                'value' => function ($data) {
//                                    return $data->status == 1 ? 'Активний' : 'Прихований';
//                                },
//                            ],

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </section>
</div>

