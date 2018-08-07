<?php
    
    use yii\helpers\Url;
    
/* 
 * Настройки профиля
 */

$this->title = 'Настройки';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <!-- Nav menu at block of profile -->
    <ul class="pager">
        <li><a href="<?= Url::to(['profile/index']) ?>">Профиль</a></li>
        <li><a class="active" href="<?= Url::to(['profile/settings-profile']) ?>">Настройки</a></li>
        <li><a href="<?= Url::to(['profile/history']) ?>">История</a></li>
    </ul>
    <!-- End nav menu at block of profile -->
    
settings-profile

</div>