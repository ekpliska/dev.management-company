<?php

    use app\modules\clients\widgets\LastNews;
    use app\modules\clients\widgets\LastServices;
    
/*
 * Главная страница личного кабинета Собственника
 */    
$this->title = Yii::$app->params['site-name'] . "Новости";
$current_date = Yii::$app->formatter->asDate(time(), 'LLLL, Y');
?>

<div class="client-page">
    <div class="row client-page__top">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="__top-counters">
                <h1>
                    <?= $current_date ?>
                </h1>
                <?= $this->render('data/indications-slider', ['indications' => $indications]) ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="__top-payments">
                <h1>
                    Платежи и квитанции
                </h1>
                <?= $this->render('data/last-payments', ['payments' => $payments]) ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="__top-promo">
                <h1>
                    Промоблок
                </h1>
                <div class="__top-promo-content">
                    <?= !empty($promo_text) ? $promo_text : '<p class="message-general-page">Информация отсутствует.</p>' ?>
                </div>
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