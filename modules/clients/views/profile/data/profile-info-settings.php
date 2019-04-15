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
<p class="profile-settings_block"><span>Пользовательские данные</span></p>
<span class="user-login-name">Логин: <?= $user_info->username ?></span>
<span class="user-role-name">Роль: <?= $user_info->role ?></span>
<span class="user-status">                
    <i class="glyphicon glyphicon-globe status-<?= $user_info->status['value'] ?>"></i> <?= $user_info->status['name'] ?>
</span>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <p class="profile-settings_block"><span>Дата регистрации</span></p>
        <span class="user-span-text">        
            <?= FormatHelpers::formatDate($user_info->dateRegister, true, 0, false) ?>
        </span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <p class="profile-settings_block"><span>Дата последнего входа</span></p>
        <span class="user-span-text">
            <?= FormatHelpers::formatDate($user_info->lastLogin, true, 0, false) ?>
        </span>
    </div>
</div>
