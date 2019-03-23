<?php

    use yii\helpers\Html;
    use app\models\Notifications;

/* 
 * Уведомления
 */
?>

<li class="nav-item dropdown">
    <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Html::img('/images/navbar/bell.svg') ?>
        <span class="<?= (count($notifications_lists) > 0) ? 'notification__dot' : 'hidden' ?>"></span>
    </a>
    <ul class="dropdown-menu in_navbar notification__dropdown">
        <?php if (!empty($notifications_lists) && count($notifications_lists) > 0) : ?>
        <li class="text-right">
            <a href="javascript:void(0);" class="notification_reset">Отметить как прочитанные</a>
        </li>
        <li class="user-info-box">
            
            <?php foreach ($notifications_lists as $note) : ?>
                <?php if ($note->type_notification == Notifications::TYPE_HAVE_MISSED_REQUEST) : ?>
                    <?= Html::a("<span class='dot'></span>{$note->message}", ['requests/view-request', 'request_number' => $note->value_1], ['class' => 'notification_link', 'data-notice' => $note->id]) ?>
                <?php elseif ($note->type_notification == Notifications::TYPE_HAVE_MISSED_PAID_REQUEST) : ?>
                    <?= Html::a("<span class='dot'></span>{$note->message}", ['paid-services/order-services'], ['class' => 'notification_link', 'data-notice' => $note->id]) ?>
                <?php endif; ?>
            <?php endforeach; ?>
            
        </li>
        <?php else: ?>
            <p>Новых уведомлений нет.</p>
        <?php endif; ?>
    </ul>
</li>
