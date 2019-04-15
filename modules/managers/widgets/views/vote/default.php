<?php

/* 
 * Процент по кадому варинту ответа
 */
?>

<?php foreach ($results as $key => $result) : ?>
<td>
    <p class="title">
        Проголосовало 
        <span class="type"><?= $key ?></span>
        <span class="count pull-right">
            <?= $result ?>%
        </span>
    </p>
</td>
<?php endforeach; ?>
<td></td>


