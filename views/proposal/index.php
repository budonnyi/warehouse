<?php

use app\models\Proposal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ProposalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пропозиції';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <section class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
                <br>
                <p>
                    <?= Html::a('Нова пропозиція', ['create'], ['class' => 'btn btn-success']) ?>
                </p>
            </div>
            <div class="card-body p-0">
                <div class="direct-chat-messages" style="height: 100%;">

                    <?php Pjax::begin(); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'id',
                            'date',
                            'title',
                            'person',
//            'content:ntext',
                            //'customer_id',
                            //'conditions:ntext',
                            //'description:ntext',
                            //'from',
                            //'status',
                            //'created_at',
                            //'updated_at',
                            [
                                'class' => ActionColumn::className(),
                                'urlCreator' => function ($action, Proposal $model, $key, $index, $column) {
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