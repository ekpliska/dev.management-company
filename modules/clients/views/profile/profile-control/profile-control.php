<?php



/* 
 * Управление профилем
 */
?>

<ul class="nav nav-tabs profile-control">
    <li class="active">
        <a data-toggle="tab" href="#rent">
            Арендатор
        </a>
    </li>
    <li>
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
    <div id="rent" class="tab-pane fade in active">
        <h3>Ареднатор</h3>
        <?= $this->render('tabs/rent') ?>
    </div>
    <div id="paymants" class="tab-pane fade">
        <h3>Платежи</h3>
        <?= $this->render('tabs/payments') ?>
    </div>
    <div id="counters" class="tab-pane fade">
        <h3>Приборы учета</h3>
        <?= $this->render('tabs/counters') ?>
    </div>
</div>