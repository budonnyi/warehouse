<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = 'Новий продукт';
$this->params['breadcrumbs'][] = ['label' => 'Продукція', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
