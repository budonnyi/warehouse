<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Proposal $model */

$this->title = 'Редагувати пропозицію: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Пропозиції', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагувати';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
