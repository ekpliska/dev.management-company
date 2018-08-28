<?php


/* 
 * Оценка заявок
 */
?>
<p>
    <span class="label label-info">Вам доступна система оценки </span>
    Оценка: <?= $scrore ?>
    <br />
    Для закрытых заявок предусмотрена система оценивания. Оценивание происходит по 5ти баольной шкале:
</p>
<div id="star" data-request="<?= $request_id ?>"></div>

<?php
$this->registerJs("
    var scoreRequest = '" . $scrore . "';
    if (scoreRequest) {
        $('div#star').raty('score', scoreRequest);
    }
")
?>
<?php
$this->registerJs("
    var request_id = $('div#star').data('request');
    $('div#star').raty({
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Оценка</h4>
            </div>
            <div class="modal-body">
                <p>Спасибо за вашу оценку</p>
                <p>
                    Предлагаем вам ответить на ряд вопросов:
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>