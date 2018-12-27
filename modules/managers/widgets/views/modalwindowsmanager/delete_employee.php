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
            'class' => 'btn btn red-outline-btn bt-bottom2 delete_empl__del', 
            'id' => 'confirm_delete-empl', 
            'data-dismiss' => 'modal']) ?>
    <?= Html::button('Отмена', ['class' => 'btn btn blue-outline-btn white-btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php Modal::end() ?>


<!--<div class="modal fade delete_empl" id="delete_disp_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Удалить дистпетчера
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите удалить диспетчера <span id="disp-fullname"></span>?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_disp__del" id ="confirm_delete-empl" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade delete_empl" id="delete_disp_manager_message" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Внимание
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Диспетчер <span id="disp-fullname"></span> имеет не закрытые заявки
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>-->
