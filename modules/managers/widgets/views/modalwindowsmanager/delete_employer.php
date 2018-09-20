<?php

/* 
 * Модальное окно
 * Удалить диспетчера
 */ 
?>
<div class="modal fade" id="delete_empl_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close delete_disp__close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Удалить сотрудника
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите удалить сотрудника 
                    <br />
                    <span id="disp-fullname"></span>
                    <br />
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger delete_disp__del" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary delete_disp__close" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_empl_manager_message" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Внимание
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Сотрудник <span id="disp-fullname"></span> имеет не закрытые заявки
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
