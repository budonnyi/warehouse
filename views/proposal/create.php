<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Proposal $model */

$this->title = 'Нова пропозиція';
$this->params['breadcrumbs'][] = ['label' => 'Пропозиції', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
    'itemModels' => $itemModels,
    'count' => $count,
]) ?>
