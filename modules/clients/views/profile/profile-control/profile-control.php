<?php



/* 
 * Управление профилем
 */
$is_rent = Yii::$app->user->can('clients_rent') ? true : false;
?>

<ul class="nav nav-tabs profile-control">
    <?php if (Yii::$app->user->can('clients')) : ?>
    <li class="active">
        <a data-toggle="tab" href="#rent">
            Арендатор
        </a>
    </li>
    <?php endif; ?>
    <li class="<?= $is_rent ? 'active' : '' ?>">
        <a data-toggle="tab" href="#paymants">
            Платежи
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#counters">
            Приборы учета
        </a>
    </li>
</ul>

<div class="tab-content profile-control">
    <?php if (Yii::$app->user->can('clients')) : ?>
    <div id="rent" class="tab-pane fade in active">
        <?= $this->render('tabs/rent', [
                'is_rent' => !empty($account_info['personal_rent_id']) ? true : false,
                'add_rent' => $add_rent,
                'rent_info' => $rent_info,
        ]) ?>
    </div>
    <?php endif; ?>
    <div id="paymants" class="tab-pane fade <?= $is_rent ? 'in active' : '' ?>">
        <?= $this->render('tabs/payments', [
                'payment_history' => $payment_history,
        ]) ?>
    </div>
    <div id="counters" class="tab-pane fade">
        <?= $this->render('tabs/counters', [
                'indications' => $counters_indication,
        ]) ?>
    </div>
</div>