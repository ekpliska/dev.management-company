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
    <?= Html::button('Удалить', ['class' => 'btn-modal btn-modal-yes delete_news__del', 'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no white-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end(); ?>

<!--<div class="modal fade" id="delete_news_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Удалить новость
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите удалить выбранную новость?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_news__del" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>-->