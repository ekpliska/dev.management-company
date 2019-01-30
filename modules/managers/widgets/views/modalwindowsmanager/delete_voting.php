<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* 
 * Модальное окно
 * Удалить голосование
 */ 
?>
<?php /* Удаление голосования */ ?>
<?php
Modal::begin([
    'id' => 'delete_voting_manager',
    'header' => 'Внимание!',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
    'bodyOptions' => [
        'class' => 'delete_voting',
    ],
]);
?>

    <p class="modal-confirm">Вы действительно хотите удалить выбранное голосование?</p>
    
    <div class="modal-footer">
        <?= Html::button('Удалить', ['class' => 'btn white-btn delete_voting__del', 'data-dismiss' => 'modal']) ?>
        <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
    </div>
    
<?php
Modal::end(); 
?>
    
<?php /* Подтверждение завершения голосования */ ?>
<?php
Modal::begin([
    'id' => 'close_voting_manager',
    'header' => 'Внимание!',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
    'bodyOptions' => [
        'class' => 'close_voting',
    ],
]);
?>

    <div class="modal-confirm">
        <div class="modal__text">
            <p></p>
        </div>
    </div>

    <div class="modal-footer">
        <?= Html::button('Завершить', ['class' => 'btn white-btn close_voting_yes', 'data-dismiss' => 'modal']) ?>
        <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
    </div>
    
<?php
Modal::end(); 
?>    

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
        <?= Html::button('Удалить', ['class' => 'btn white-btn delete_question', 'data-dismiss' => 'modal']) ?>
        <?= Html::button('Отмена', ['class' => 'btn red-btn', 'data-dismiss' => 'modal']) ?>
    </div>
    
<?php
Modal::end(); 
?>