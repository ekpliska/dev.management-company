<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Вывод пунктов меню
 */
$count = 0;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>

<ul class="menu-items menu-scroll" id="menu">
    
    <?php foreach ($menu_array as $key => $item) : ?>
        <li id="item-menu" class="<?= ($controller == $key) ? 'active-item' : '' ?> <?= $key == 'personal-account' ? 'personal-account-menu' : '' ?>">
            <a href="<?= Url::to([$item['link']]) ?>">
                <?= $item['name'] ?>
            </a>
            <?php if ($key == 'personal-account') : ?>
                <?php foreach ($child_array as $key_child => $child_item) : ?>
                    <?php $action_url = explode('/', $child_item['link']); ?>
            
                    <a href="<?= Url::to([$child_item['link']]) ?>" class="sub-menu-item <?= ($action == $action_url[1]) ? 'active-sub-item' : '' ?>">
                        <?= $child_item['name'] ?>
                    </a>
            
                <?php endforeach; ?>
            <?php endif; ?>
        </li>
        <?php $count++ ?>
    <?php endforeach; ?>
        
</ul>