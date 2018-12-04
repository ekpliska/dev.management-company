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
    
    <?php foreach ($menu_array as $key => $item) : ?>
        <li id="item-menu-<?= $count ?>" class="<?= ($count == 2) ? 'active-item' : '' ?>">
            <a href="<?= Url::to([$item['link']]) ?>">
                <?= $item['name'] ?>
            </a>
            <?php if ($key == 'personal-account') : ?>
                <?php foreach ($child_array as $key_child => $child_item) : ?>
                    <a href="<?= Url::to([$child_item['link']]) ?>" class="sub-menu-item"><?= $child_item['name'] ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </li>
        <?php $count++ ?>
    <?php endforeach; ?>
        
</ul>