<?php

    use app\modules\clients\widgets\LastNews;
    

/*
 * Главная страница личного кабинета Собственника
 */    
$this->title = Yii::$app->params['site-name'] . "Главная";
?>

<div class="client-page">
    <div class="row client-page__top">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="__top-counters">
                <h1>Month Year</h1>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="__top-payments">
                <h1>Month Year</h1>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="__top-promo">
                <h1>Month Year</h1>
            </div>
        </div>        
    </div>
    <div class="row client-page__services">
        <h1>Услуги</h1>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="service-item">1</div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="service-item">1</div>
        </div>
    </div>
    <div class="row client-page__news">
        <h1>Новости</h1>
        <?= LastNews::widget(['living_space' => $living_space]) ?>
    </div>
</div>