<?php

/* 
 * Модальное окно
 * Редактирование описания ЖК
 */ 
?>
<?php /* Удаление голосования */ ?>
<div class="modal fade edit_houses_modal" id="edit-description-house" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Редактирование описание, дом: ###
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    form
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger edit_houses__save" data-dismiss="modal">Сохранить</button>
                <button class="btn btn-primary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
