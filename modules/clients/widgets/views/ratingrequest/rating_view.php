<?php


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<p>
    <span class="label label-info">Вам доступна система оценки</span>
    Для закрытых заявок предусмотрена система оценивания. Оценивание происходит по 5ти баольной шкале:
</p>
<div id="star"></div>
<?php
$this->registerJs("
    $('div#star').raty({
        click: function(score) {
            $.ajax({
                url: 'add-score-request',
                method: 'POST',
                data: {
                    score: score,
                },
                success: function(data) {
                    console.log('thanks for score, you score is ' + data.score);
                },
                error: function() {
                    console.log('Error');
                }
            });
        }
    });
    
    // Установить текущий рейтинг
    // $('div#star').raty('score', 4);

");
?>
