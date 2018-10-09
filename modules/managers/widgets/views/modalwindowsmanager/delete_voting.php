<?php

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

<?php /* Удаление вопроса */ ?>
<div class="modal fade delete_question_vote" id="delete_question_vote_message" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
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
                <button class="btn btn-primary" data-dismiss="modal">Удалить</button>
                <button class="btn btn-primary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>