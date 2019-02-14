<?php

    use yii\helpers\Html;
    use app\helpers\FormatFullNameUser;

/* 
 * Уведомления о текущих не закрытых заявках на платные услуги
 */
$key_account = 0;    
?>

<?php if (isset($user_lists) && (!empty($user_lists))) : ?>
<?php foreach ($user_lists as $key => $user) : ?>
<div class="notice__user_block <?= $key == 0 ? 'notice__user__active' : '' ?>" id="user-<?= $user['user']['user_id'] ?>" data-user="<?= $user['user']['user_id'] ?>" data-type="paid-requests">
    <?php $user_avatar = $user['user']['user_photo'] ? $user['user']['user_photo'] : '@web/images/no-avatar.jpg'; ?>
    <?= Html::img($user_avatar, ['class' => '']) ?>
    <span class="notice__username">
        <?= FormatFullNameUser::nameEmployee($user['clients_surname'], $user['clients_name'], null, true) ?>
    </span>
    <span class="notice__count">
        <?php 
            // Подсчет количества заявок по всем лицевым счетам конкретного пользователя
            $count_paid_request = 0; 
            foreach ($user['personalAccount'] as $key_account => $value) {
                $count_paid_request += count($user['personalAccount'][$key_account]['paidRequest']);
            }
        ?>
        <?= $count_paid_request ?>
    </span>
</div>
<?php endforeach; ?>
<?php else: ?>
    <div class="notice info">
        <p>Список пуст.</p>
    </div>
<?php endif; ?>
