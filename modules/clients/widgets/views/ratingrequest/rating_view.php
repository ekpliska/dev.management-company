<?php

    use yii\helpers\Html;

/* 
 * Оценка заявок
 */
?>

<?php if ($score == 0) : ?>
    <?= Html::button('Вам доступна система оценки <i class="glyphicon glyphicon-question-sign"></i>', [
        'class' => 'btn btn-info btn-sm',
        'title' => 'Система оценки заявки',
        'data-toggle' => 'popover',
        'data-trigger' => 'focus',
        'data-content' => 'Для закрытых заявок доступна система оценки заявки по пятибоальной системе'
    ]) ?>
<?php endif; ?>

<div id="star" data-request="<?= $request_id ?>" data-score-reguest="<?= $score ?>"></div>

<div id="score-modal-message" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Оценка заявки</h4>
            </div>
            <div class="modal-body">
                <p>Спасибо! Ваша оценка принята</p>
                <p>
                    Так же предлагаем вам ответить на ряд вопросов:
                    // TODO
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ответить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>