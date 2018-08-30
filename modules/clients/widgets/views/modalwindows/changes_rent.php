<?php

/* 
 * Модальное окно, активизируется, когда нажат checkbox Арендатор 
 * Профиль Собственника
 */ 
?>
<div class="modal fade" id="changes_rent" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><button class="close changes_rent__close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-header">
                <h4 class="modal-title">
                    Дальнейшее действия с учетной записью арендатора
                </h4>
            </div>
            <div class="modal-body">
                <div class="modal__text">
                    Какое действие вы хотите совершить с учетной записью арендатора? 
                    <br />
                    <span id="rent"></span>
                    <span id="rent-surname"></span>
                    <span id="rent-name"></span>
                    <span id="rent-second-name"></span>
                    <br />
                    *** Внимание, при удалении арендатора так же будет удалена его учетная запись на портале
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger changes_rent__del" data-dismiss="modal">Удалить</button>
                <button class="btn btn-success changes_rent__undo" data-dismiss="modal">Отвязать от лицевого счета</button>
                <button class="btn btn-primary changes_rent__close" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
