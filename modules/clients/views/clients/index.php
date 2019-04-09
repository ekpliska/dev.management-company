<?php

    use app\modules\clients\widgets\LastNews;
    use app\modules\clients\widgets\LastServices;
    
/*
 * Главная страница личного кабинета Собственника
 */    
$this->title = Yii::$app->params['site-name'] . "Новости";
$current_date = date('F, Y');
?>

<div class="client-page">
    <div class="row client-page__top">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="__top-counters">
                <h1>
                    <?= $current_date ?>
                </h1>
                <div class="__top-counters_content">
                    #todo
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="__top-payments">
                <h1>
                    Платежи и квитанции
                </h1>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="__top-promo">
                <h1>
                    Промоблок
                </h1>
            </div>
        </div>        
    </div>
    <div class="row client-page__services">
        <?= LastServices::widget() ?>
    </div>
    <div class="row client-page__news">
        <?= LastNews::widget(['living_space' => $living_space]) ?>
    </div>
</div>