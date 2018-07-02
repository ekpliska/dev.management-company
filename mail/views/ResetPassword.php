<?php

/* 
 * Форма письма для восстановление пароля
 */
$link = Yii::$app->urlManager->createAbsoluteUrl(['email-confirm', 'token' => $user->email_confirm_token]);
?>
Здравствуйте, <?= $user->user_login ?>
<br />
Вы запросили восстановление пароля, ваш новый пароль: <?= $new_password ?>