<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Полный профиль пользователя на странице "Настройки"
 */
?>

<p class="user-full-name">
    <?=  $user_info->surname . ' '. $user_info->name . ' ' . $user_info->secondName ?>
</p>
<p class="profile-settings_block"><span>Пользовательские данные</span></p>
    <span class="user-login-name">Логин: <?= $user_info->username ?></span>
    <span class="user-role-name">Роль: <?= $user_info->role ?></span>
    <span class="user-status">                
        <i class="glyphicon glyphicon-globe status-<?= $user_info->status['value'] ?>"></i> <?= $user_info->status['name'] ?>
    </span>
            
<p class="profile-settings_block"><span>Адрес</span></p>
    <span class="user-span-text">
        <i class="fa fa-map-marker"></i> <?= $user_info->getFullAdress($this->context->_choosing) ?>
    </span>
        
<p class="profile-settings_block"><span>Дата регистрации</span></p>
    <span class="user-span-text">        
        <?= FormatHelpers::formatDate($user_info->dateRegister, true, 0, false) ?>
    </span>
        
<p class="profile-settings_block"><span>Дата последнего входа</span></p>
    <span class="user-span-text">
        <?= FormatHelpers::formatDate($user_info->lastLogin, true, 0, false) ?>
    </span>
