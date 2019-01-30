<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Подтверждение удаления заявки, платной услуги
 */
?>

<?php
Modal::begin([
    'id' => 'delete-request-message',
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

<p class="modal-confirm">Вы действительно хотите удалить заявку?</p>

<div class="modal-footer">
    <?= Html::button('Удалить', ['class' => 'btn white-btn delete_request', 'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>