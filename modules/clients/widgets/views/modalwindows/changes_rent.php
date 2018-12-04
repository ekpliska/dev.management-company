<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно, активизируется, когда нажат checkbox Арендатор 
 * Профиль Собственника
 */ 
?>

<?php
Modal::begin([
    'id' => 'changes_rent',
    'header' => 'Внимание!',
    'closeButton' => [
        'class' => 'close modal-close-btn changes_rent__close',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
]);
?>

    <p class="modal-confirm">Данные арендатора будут удалены! Продолжить?</p>

    <div class="modal-footer no-border">
        <?= Html::button('Удалить', ['class' => 'btn blue-outline-btn white-btn mx-auto changes_rent__del']) ?>
        <?= Html::button('Отмена', ['class' => 'btn red-outline-btn bt-bottom2 changes_rent__close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php Modal::end(); ?>