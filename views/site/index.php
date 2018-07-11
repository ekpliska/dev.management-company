<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;

$this->title = 'Customers | Вход';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-5">
                <h2>Главная</h2>
                <br />
                <p>Имя пользователя: <?= Yii::$app->user->identity->user_login ?></p>
                <p>Роль: <?= (Yii::$app->authManager->getRole('clients')->description) ?></p>
            </div>
            <div class="col-lg-7">
                <h2>Модули</h2>
                <a href="<?= Url::to(['clients/clients']) ?>" class="btn btn-default">Клиенты</a>
                <a href="<?= Url::to(['houses/index']) ?>" class="btn btn-default">Клиенты</a>
            </div>
        </div>

    </div>
</div>
