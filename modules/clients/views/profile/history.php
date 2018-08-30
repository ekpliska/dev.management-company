<?php

    use app\modules\clients\widgets\SubMenuProfile;

/* 
 * История, раздел Профиль
 */
$this->title = 'История';
?>

<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= SubMenuProfile::widget() ?>
    
</div>
