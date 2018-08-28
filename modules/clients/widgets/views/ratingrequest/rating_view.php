<?php

    use yii\helpers\Html;

/* 
 * Оценка заявок
 */
?>
<?php if ($score == 0) : ?>
    <?= Html::button('? Вам доступна система оценки', [
        'class' => 'btn btn-info btn-sm',
        'title' => 'Система оценки заявки',
        'data-toggle' => 'popover',
        'data-trigger' => 'focus',
        'data-content' => 'Для закрытых заявок доступна система оценки заявки по пятибоальной системе'
    ]) ?>
<?php endif; ?>
<div id="star" data-request="<?= $request_id ?>"></div>

<?php
$this->registerJs("
    $('[data-toggle=\'popover\']').popover();
    var request_id = $('div#star').data('request');
    var scoreRequest = " . $score . ";
    $('div#star').raty({
        score: scoreRequest,
        readOnly: scoreRequest === 0 ? false : true,
        click: function(score) {
            $.ajax({
                url: 'add-score-request',
                method: 'POST',
                data: {
                    score: score,
                    request_id: request_id,
                },
                success: function(data) {
                    console.log('thanks for score, you score is ' + data.score);
                    $('#score-modal-message').modal('show');
                },
                error: function() {
                    console.log('Error');
                }
            });
        }
    });
    
");
?>
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
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ответить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>