<?php

    use yii\helpers\Html;

/* 
 * Оценка заявок
 */
$id_name = "start-{$request_id}";
?>

<div id="<?= $id_name ?>" data-request="<?= $request_id ?>" data-score-reguest="<?= $score ?>"></div>

<?php
$grade = $score ? $score : 0; 
$this->registerJs("
$('div#" . $id_name . "').raty({
    score: " . $grade . ",
    readOnly: true,
});    
")
?>
