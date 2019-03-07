<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Уведомления
 */
?>

<li class="nav-item dropdown">
    <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Html::img('/images/navbar/bell.svg') ?>
    </a>
    <ul class="dropdown-menu in_navbar notification__dropdown">
        <?php if (!empty($notifications_lists) && count($notifications_lists) > 0) : ?>
        <li class="text-right">
            <a href="javascript:void(0);" class="notification_reset">Отметить как прочитанные</a>
        </li>
        <li class="user-info-box">
            <?= Html::a('<span class="dot"></span> TODO', ['/'], ['class' => 'notification_link']) ?>
        </li>
        <?php else: ?>
            <p>Новых уведомлений нет.</p>
        <?php endif; ?>
    </ul>
</li>
