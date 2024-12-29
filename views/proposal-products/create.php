<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProposalProducts $model */

$this->title = 'Create Proposal Products';
$this->params['breadcrumbs'][] = ['label' => 'Proposal Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
