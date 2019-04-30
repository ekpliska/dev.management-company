<?php

    use app\models\StatusRequest;

/* 
 * Оперативная информация по заявкам и платным услугам
 */
?>

<div class="panel-group requests__info">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse1">
                    Оперативная информация за текущий месяц
                    <i class="glyphicon glyphicon-menu-down"></i>
                </a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                <ul class="requests__info-list">
                    <?php foreach ($results as $key => $result) : ?>
                    <li class="<?= "item-st-{$key}" ?>">
                        <p class="count">
                            <?= $result ?>
                        </p>
                        <p class="status_name">
                            <?= StatusRequest::statusName($key) ?>
                        </p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

