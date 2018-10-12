<?php

    use yii\helpers\Html;

/* 
 * Характеристики по дому
 */
?>

    <?php if (isset($characteristics) && $characteristics) : ?>
        <?php foreach ($characteristics as $characteristic) : ?>
        <tr>
            <td><?= $characteristic['characteristics_name'] ?>: <?= $characteristic['characteristics_value'] ?></td>
            <td>
                <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                        'id' => 'delete-characteristic__link',
                        'data-characteristic-id' => $characteristic['characteristics_id'],
                    ]) 
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?> 
