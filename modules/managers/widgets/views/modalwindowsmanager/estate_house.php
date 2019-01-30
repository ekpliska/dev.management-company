<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

?>

<?php
    // Ошибка добавления характеристики, загрузки документа
    Modal::begin([
        'id' => 'estate_house_message_manager',
        'header' => 'Внимание!',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>

    <div class="modal__text"></div>
        
    <div class="modal-footer">
        <?= Html::button('OK', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php Modal::end(); ?>

<?php
    // Подтверждение удаления статуса Должник и примечания у квартиры
    Modal::begin([
        'id' => 'estate_note_message_manager',
        'header' => 'Внимание',
        'closeButton' => [
            'class' => 'close modal-close-btn estate_note_message__yes',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>

    <div class="modal__text">Вы действительно хотите снять статус "Должник" с выбранной квартиры?</div>
        
    <div class="modal-footer">
        <?= Html::button('Да', ['class' => 'btn-modal btn-modal-yes estate_note_message__yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no estate_note_message__no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php Modal::end(); ?>