<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Вывод пунктов меню
 */
$count = 0;
$controller = Yii::$app->controller->id;
?>

<?php 
// Если пользователь с правами арендатора, то из списка меню удаляем пункт "Опрос"
if (Yii::$app->user->can('clients_rent')) {
    unset($menu_array['voting']);
}
?>

<ul class="menu-clients__navbar">
    <?php foreach ($menu_array as $key => $item) : ?>
        <li>
            <a href="<?= Url::to([$item['link']]) ?>" class="<?= $controller == $key ? 'active-item-menu' : '' ?>">
                <?= $item['name'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>