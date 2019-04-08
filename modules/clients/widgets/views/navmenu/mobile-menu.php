<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Навигационное меню для мобильной версии
 * Разрешение экрана < 991px
 */
?>

<ul class="navbar_mobile">
    <?php foreach ($menu_array as $key => $item) : ?>
    <li>
        <a href="<?= Url::to([$item['link']]) ?>" class="item-mobile-menu">
            <?= $item['name'] ?>
        </a>
    </li>
    <?php endforeach; ?>
    <li>
        <a href="<?= Url::to(['profile/index']) ?>" class="item-mobile-menu">
            Профиль
        </a>            
    </li>
</ul>