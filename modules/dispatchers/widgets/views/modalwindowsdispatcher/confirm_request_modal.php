<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно для управления заявками, платными услугами
 */
?>

<?php
Modal::begin([
    'id' => 'confirm-reject-request-message',
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

<p class="modal-confirm">Вы действительно хотите отклонить заявку?</p>

<div class="modal-footer">
    <?= Html::button('Отклонить', ['class' => 'btn white-btn request_reject_yes', 'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>

<?php
Modal::begin([
    'id' => 'confirm-request-error',
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

<p class="modal-confirm">Произошла ошибка. Попробуйте обновить страницу браузера или повторить попытку через несколько минут.</p>

<div class="modal-footer">
    <?= Html::button('Закрыть', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>