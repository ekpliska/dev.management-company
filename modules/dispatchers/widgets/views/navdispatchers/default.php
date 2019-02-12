<?php

    use yii\helpers\Url;

/* 
 * Меню, диспетчеры
 */
$count = 0;    
?>
<ul class="menu-items menu-scroll" id="menu">
    
    <?php foreach ($menu_array as $key => $item) : ?>
        <li id="item-menu-<?= $count ?>" class="<?= ($count == 2) ? 'active-item' : '' ?>">
            <a href="<?= Url::to([$item['link']]) ?>">
                <?= $item['name'] ?>
            </a>
        </li>
        <?php $count++ ?>
    <?php endforeach; ?>
        
</ul>