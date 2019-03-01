<?php
    
    use yii\helpers\Html;

$this->title = 'Customers | Вход';
?>

<div class="start-page">
    <p class="start-page__title">Быcтро и удобно</p>
    <p class="start-page__welcome-txt">
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
    </p>
    
    <div class="start-page__btn-block">
        <div class="text-center">
            <?= Html::a('Зарегистрироваться', ['signup/index'], ['class' => 'btn btn-link white-to-blue-link']) ?>
            <?= Html::a('Войти', ['site/login'], ['class' => 'btn btn-link blue-btn-link']) ?>
        </div> 
    </div>    
</div>