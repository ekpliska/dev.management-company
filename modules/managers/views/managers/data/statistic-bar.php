<?php

    use yii\helpers\Html;

/* 
 * Навбар статистики для главной страницы
 */
?>

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="top_bar__block">
        <div class="top_bar__block__header">
            <h4>Заявки</h4>
            <p>
                Новых заявок
                <span class="pull-right">
                    <?= $count_request ?>
                </span>
            </p>
        </div>
        <div class="top_bar__block__footer">
            <?= Html::a('Все зявки', ['requests/index']) ?>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="top_bar__block">
        <div class="top_bar__block__header">
            <h4>Платные услуги</h4>
            <p>
                Новых заявок
                <span class="pull-right">
                    <?= $count_paid_request ?>
                </span>
            </p>
        </div>
        <div class="top_bar__block__footer">
            <?= Html::a('Все платные услуги', ['paid-requests/index']) ?>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="top_bar__block">
        <div class="top_bar__block__header">
            <h4>Опросы</h4>
            <p>
                Активных опросов
                <span class="pull-right">
                    <?= $count_active_vote ?>
                </span>
            </p>
        </div>
        <div class="top_bar__block__footer">
            <?= Html::a('Все опросы', ['voting/index']) ?>
        </div>
    </div>
</div>