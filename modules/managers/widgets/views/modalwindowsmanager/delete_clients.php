<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно
 * Удалить собственника
 */ 
?>

<?php
Modal::begin([
    'id' => 'delete_clients_manager',
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

<p class="modal-confirm">Вы действительно хотите удалить пользователя <span id="client-fullname"></span> из системы?</p>

<div class="modal-footer">
    <?= Html::button('Удалить', [
            'class' => 'btn white-btn delete_client__del', 
            'id' => 'confirm_delete-empl', 
            'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>