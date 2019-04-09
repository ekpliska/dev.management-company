<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Данные пользователя, настройки профиля
 */
?>

<p class="user-full-name">
    <?= $user_info->fullNameClient ?>
</p>
<span class="user-login-name">Логин: <?= $user_info->username ?></span>
<span class="user-role-name">Роль: <?= $user_info->role ?></span>
<span class="user-status">                
    <i class="glyphicon glyphicon-globe status-<?= $user_info->status['value'] ?>"></i> <?= $user_info->status['name'] ?>
</span>