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
        <a href="javascript:void(0)" id="sub-menu_open">Лицевой счет <i class="caret-arrow down-arrow"></i></a>
        <ul class="mobile-menu__items__sub">
            <li><?= Html::a($child_array['0']['name'], [$child_array['0']['link']], ['class' => '']) ?></li>
            <li><?= Html::a($child_array['1']['name'], [$child_array['1']['link']], ['class' => '']) ?></li>
            <li><?= Html::a($child_array['2']['name'], [$child_array['2']['link']], ['class' => '']) ?></li>
        </ul>
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
<?php /*
    <li>
        <?= Html::a('Уведомления', ['/'], ['class' => '']) ?>
    </li>
*/ ?>
    <li>
        <?= Html::a('Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>', ['/site/logout'], [
                'data' => [
                    'method' => 'post'], 
                    'class' => 'float-right footer_link-logout']) ?>
    </li>
    
</ul>