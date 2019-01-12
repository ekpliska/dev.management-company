<?php

/* 
 * Процент по кадому варинту ответа
 */
?>

<table class="table table-voting-results">
    <tr>
        <?php foreach ($results as $key => $result) : ?>
            <td>
                <p class="title"><?= $key ?></p>
                <p class="results"><?= $result ?>%</p>
            </td>
        <?php endforeach; ?>
    </tr>
</table>