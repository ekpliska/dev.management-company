<?php

    use yii\helpers\Url;

/* 
 * Меню, диспетчеры
 */
$count = 0;
$controller = Yii::$app->controller->id;
?>
<ul class="menu-items menu-scroll" id="menu">
    
    <?php foreach ($menu_array as $key => $item) : ?>
        <li id="item-menu" class="<?= ($key == $controller) ? 'active-item' : '' ?>">
            <a href="<?= Url::to([$item['link']]) ?>">
                <?= $item['name'] ?>
            </a>
        </li>
        <?php $count++ ?>
    <?php endforeach; ?>
        
</ul>