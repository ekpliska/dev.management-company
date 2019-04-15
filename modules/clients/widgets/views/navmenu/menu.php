<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Вывод пунктов меню
 */
$count = 0;
$controller = Yii::$app->controller->id;
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