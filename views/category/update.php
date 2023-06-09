<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Category $model */

$this->title = 'Редагування категорії: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категорії', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагувати';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            <h1><?= Html::encode($this->title) ?></h1>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </section>
</div>
