<?php

    use yii\helpers\Html;

/*
 * Меню для мобильной версии
 */ 
?>
<?php if (!empty($menu_array) && is_array($menu_array)) : ?>
<ul class="mobile-menu__items">
    <li>
        <?= Html::a($menu_array['dispatchers']['name'], [$menu_array['dispatchers']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['requests']['name'], [$menu_array['requests']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['news']['name'], [$menu_array['news']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['housing-stock']['name'], [$menu_array['housing-stock']['link']], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a($menu_array['reports']['name'], [$menu_array['reports']['link']], ['class' => '']) ?>
    </li>
    <hr class="mobile-menu__hr">
    <li>
        <?= Html::a('Профиль', ['profile/index'], ['class' => '']) ?>
    </li>
    <li>
        <?= Html::a('Уведомления', ['/'], ['class' => '']) ?>
    </li>
</ul>
<?php endif; ?>