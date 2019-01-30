<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно
 * Удалить новость
 */ 
?>

<?php
Modal::begin([
    'id' => 'delete_news_manager',
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

<p class="modal-confirm">Вы действительно хотите удалить выбранную публикацию?</p>

<div class="modal-footer">
    <?= Html::button('Удалить', ['class' => 'btn white-btn delete_news__del', 'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end(); ?>