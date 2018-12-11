<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

/* 
 * Выпадающее навигационное меню, Администраторы
 */
$controller = Yii::$app->controller->id;
?>
<?php if (isset($menu)) : ?>
<div class="menu-wrap-manager">
    <div class="menu-sidebar-manager">
        <ul class="menu">
            <?php foreach ($menu as $key => $item) :  ?>
                <li class="<?= $controller == $key ? 'active' : '' ?>">
                    <a href="<?= Url::to([$item['link']]) ?>">
                        <?= $item['name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>