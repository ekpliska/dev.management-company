<?php

    use yii\helpers\Html;

/* 
 * Модальное окно объединить арендатора с лицевым счетом
 * Профиль Собственника
 */

?>
<div class="modal fade" id="bind-rent-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Объеденить арендатора с лицевым счетом</h4>
            </div>
            <div class="modal-body">
                <p>Для продолжения процедуры объединения арендатора с лицевым счетом выберите из списка необходимый лицевой счет.</p>
                <div class="row">
                    <div class="col-md-5">
                        <span class="fullname"></span>
                    </div>
                    <div class="col-md-2">
                        <span class="glyphicon glyphicon-random"></span>
                    </div>
                    <div class="col-md-5">
                        <?php if ($list_account) : ?>
                            <?= Html::dropDownList('_list-account-rent', null, $list_account, ['class' => 'form-control', 'id' => '_list-account-rent']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-ok-bind">Объединить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
