<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно
 * Удалить голосование
 */ 
?>
<?php /* Удаление голосования */ ?>
<div class="modal fade delete_voting" id="delete_voting_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Удалить голосования
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите удалить выбранное голосование?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_voting__del" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<?php /* Подтверждение завершения голосования */ ?>
<div class="modal fade close_voting" id="close_voting_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Завершение голосования
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    <p></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger close_voting_yes" data-dismiss="modal">Завершить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<?php /* Удаление вопроса */ ?>

<?php /* Удаление вопроса */ ?>
<?php
Modal::begin([
    'id' => 'delete_question_vote_message',
    'header' => 'Внимание!',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
    'bodyOptions' => [
        'class' => 'delete_question_vote',
    ],
]);
?>

    <p class="modal-confirm">Вы действительно хотите удалить вопрос?</p>
    <div class="modal-footer">
        <?= Html::button('Удалить', ['class' => 'btn btn red-outline-btn bt-bottom2 delete_question', 'data-dismiss' => 'modal']) ?>
        <?= Html::button('Отмена', ['class' => 'btn btn blue-outline-btn white-btn', 'data-dismiss' => 'modal']) ?>
    </div>
    
<?php
Modal::end(); 
?>

<!--<div class="modal fade delete_question_vote" id="delete_question_vote_message" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Удаление вопроса
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите удалить вопрос?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_question" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>-->