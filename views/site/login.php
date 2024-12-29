<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вхід';

?>

<div class="container-fluid">
    <div class="row">
        <div class="login">
            <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

            <p style="text-align: center">Для входу заповніть наступні поля:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-12 col-form-label mr-lg-12'],
                    'inputOptions' => ['class' => 'col-lg-12 form-control'],
                    'errorOptions' => ['class' => 'col-lg-12 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <!--            --><?php //= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div style="text-align: center">
                    <?= Html::submitButton('Вхід', ['class' => 'btn btn-primary loginBtn', 'name' => 'login-button']) ?>
                </div>
            </div>

<!--            <div style="text-align: center">-->
<!--                Якщо забули пароль його можна --><?php //= Html::a('скинути', ['site/request-password-reset']) ?><!--.-->
<!--            </div>-->

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script>
    $('.content-wrapper').removeClass('content-wrapper');
</script>
<style>
    body {
        background-image: url(../background.png);
        background-repeat: no-repeat;
        background-position: center center;
        background-attachment: fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    .login {
        margin: 0 auto;
        margin-top: 80px;
        margin-bottom: 40px;
        border: 1px solid #000;
        padding: 30px;
        border-radius: 15px;
        background-color: azure
    }

    .loginBtn {
        width: 70%;
    }
</style>