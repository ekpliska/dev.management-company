<?php

/* 
 * Модальное окно
 * Удалить диспетчера
 */ 
?>
<div class="modal fade" id="delete_disp_manager" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close delete_disp__close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Удалить диспетчера
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Вы действительно хотите удалить диспетчера 
                    <br />
                    <span id="disp-surname"></span>
                    <span id="disp-name"></span>
                    <span id="disp-second-name"></span>
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

<div class="modal fade" id="delete_disp_manager_message" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Внимание
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Диспетчер 
                    <span id="disp-surname"></span>
                    <span id="disp-name"></span>
                    <span id="disp-second-name"></span>
                    имеет не закрытые заявки. Удалить диспетчера не возможно!
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
