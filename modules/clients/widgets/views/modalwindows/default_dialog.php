<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно по умолчанию
 */
?>

<?php
Modal::begin([
    'id' => 'default_modal-message',
    'header' => 'Отправка квитанции',
    'closeButton' => [
        'class' => 'close close modal-close-btn',
    ],
]);
?>

    <div id="default_modal-message__text"></div>

    <div class="modal-footer">
        <?= Html::button('Закрыть', [
                'data-dismiss' => 'modal', 
                'class' => 'btn-modal btn-modal-no',
        ]) ?>
    </div>
<?php Modal::end(); ?> 


