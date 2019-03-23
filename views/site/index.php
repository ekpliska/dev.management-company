<?php
    
    use yii\helpers\Html;

$this->title = 'Customers | Вход';
?>

<div class="start-page">
    <div class="start-page__welcome-txt">
        <?= $welcome_text ?>
    </div>
    
    <div class="start-page__btn-block">
        <div class="text-center">
            <?= Html::a('Зарегистрироваться', ['signup/index'], ['class' => 'btn btn-link white-to-blue-link']) ?>
            <?= Html::a('Войти', ['site/login'], ['class' => 'btn btn-link blue-btn-link']) ?>
        </div> 
    </div>    
</div>