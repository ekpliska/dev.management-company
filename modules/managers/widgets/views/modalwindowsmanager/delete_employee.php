<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно
 * Удалить сотрудника
 */ 
?>

<?php
Modal::begin([
    'id' => 'delete_employee_manager',
    'header' => 'Внимание!',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],    
]);
?>

<p class="modal-confirm">Вы действительно хотите удалить пользователя <span id="disp-fullname"></span> из системы?</p>

<div class="modal-footer">
    <?= Html::button('Удалить', [
            'class' => 'btn white-btn delete_empl__del', 
            'id' => 'confirm_delete-empl', 
            'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>

<?php
Modal::begin([
    'id' => 'delete_employee_error',
    'header' => 'Ошибка!',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],    
]);
?>

<p class="modal-confirm">Данный сотрудник имеет не закрытые заявки!</p>

<div class="modal-footer">
    <?= Html::button('Закрыть', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>