<?php

/* 
 * Модальное окно на подтверждение удаления аредатора
 * Профиль собственника
 */
?>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Подтверждение удаления</h4>
            </div>
            <div class="modal-body">
                <p>Вы собираетесь удалить арендатора <b><i class="title"></i></b> и его учетную запись на портале.</p>
                <p>Продолжить?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-ok-delete">Удалить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
