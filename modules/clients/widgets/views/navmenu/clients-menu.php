<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Вывод пунктов меню
 */
$count = 0;
$controller = Yii::$app->controller->id;
?>

<ul class="menu-items menu-scroll" id="menu">
    
    <?php foreach ($menu_array as $key => $items) : ?>
        <?php foreach ($items as $key_item => $item) : ?>
            <li id="item-menu-<?= $key ?>" class="<?= ($key == 2) ? 'active-item' : '' ?>">
                <a href="<?= Url::to([$key_item . '/index']) ?>"><?= $item ?></a>
            </li>
            <?php $count++ ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
        
</ul>