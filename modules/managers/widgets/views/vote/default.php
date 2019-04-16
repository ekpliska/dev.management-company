<?php

/* 
 * Процент по кадому варинту ответа
 */
?>

<?php foreach ($results as $key => $result) : ?>
<td>
    <div class="result-item">
        <div class="<?= round($result, 0) != 0 ? 'result-count-is' : 'result-count' ?>" style="width: <?= round($result, 0) != 0 ? round($result, 0) : 100 ?>%">
        </div>
        <span class="title <?= round($result, 0) != 0 ? '' : '' ?>">
            <?= $key . ' ' . round($result, 0) ?>%
        </span>
    </div>
</td>
<?php endforeach; ?>
<td></td>