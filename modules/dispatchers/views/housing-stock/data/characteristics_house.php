<?php

    use yii\helpers\Html;

/* 
 * Характеристики по дому
 */
?>

<?php if (isset($characteristics) && $characteristics) : ?>
    <table class="table table-characteristics table-striped ">
        <?php foreach ($characteristics as $characteristic) : ?>
        <tr>
            <td><?= $characteristic['characteristics_name'] ?>: <?= $characteristic['characteristics_value'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<div class="notice info">
    <p>Характеристики не заданы</p>
</div>
<?php endif; ?> 
