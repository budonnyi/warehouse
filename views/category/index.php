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

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <?= Html::a('Нова категорія', ['create'], ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="direct-chat-messages" style="height: 100%;">

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
            </div>
        </div>
    </section>
</div>

