<?php

    use yii\helpers\Html;

/* 
 * Навигационное меню, для блока Настройки
 */
$action = Yii::$app->controller->action->id;
?>
<ul class="settings__sub-menu">
    <?php foreach ($sub_nav as $key => $item) : ?>
        <li class="<?= $action == $key ? 'active' : '' ?>">
            <?= Html::a($item['label'], ["{$item['url']}"]) ?>
        </li>
    <?php endforeach; ?>
</ul>
