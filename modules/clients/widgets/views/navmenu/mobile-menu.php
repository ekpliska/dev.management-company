<?php

    use yii\helpers\Html;

/* 
 * Навигационное меню для мобильной версии
 * Разрешение экрана < 991px
 */
?>
<ul class="mobile-menu__items">
    <li>
        <?= Html::a($menu_array['clients']['name'], [$menu_array['clients']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['requests']['name'], [$menu_array['requests']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['personal-account']['name'], [$menu_array['personal-account']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['paid-services']['name'], [$menu_array['paid-services']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['voting']['name'], [$menu_array['voting']['link']], ['class' => '']) ?>
    </li>
    <hr class="mobile-menu__hr">
    <li>
        <?= Html::a('Профиль', ['profile/index'], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a('Уведомления', ['/'], ['class' => '']) ?>
    </li>
    
</ul>