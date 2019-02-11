<?php

    use yii\helpers\Html;

/* 
 * Навигационное меню, Настройки
 */
?>
<ul class="settings__sub-menu">
    <li class="active">
        <?= Html::a('Управляющая организация', ['settings/index']) ?>
    </li>
    <li>
        <?= Html::a('Подразделения/Должности', ['/']) ?>
    </li>
    <li>
        <?= Html::a('Пратнеры', ['/']) ?>
    </li>
    <li>
        <?= Html::a('Настройка слайдера', ['/']) ?>
    </li>
    <li>
        <?= Html::a('API', ['/']) ?>
    </li>
    <li>
        <?= Html::a('Частыне вопросы', ['/']) ?>
    </li>
</ul>