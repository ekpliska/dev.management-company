<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;

$this->title = 'Customers | Вход';
?>

<div class="start-page">
    <p class="start-page__title">Быcтро и удобно</p>
    <p class="start-page__welcome-txt">
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
    </p>
    
    <div class="start-page__btn-block">
        <div class="text-center">
            <?= Html::a('Зарегистрироваться', ['signup/index'], ['class' => 'btn white-to-bue-btn']) ?>
            <?= Html::a('Войти', ['site/login'], ['class' => 'btn blue-btn']) ?>
        </div> 
    </div>    
</div>