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
            <td>
                <?= Html::button('', [
                        'id' => 'delete-characteristic__link',
                        'class' => 'btn',
                        'data-characteristic-id' => $characteristic['characteristics_id'],
                    ]) 
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>        
<?php endif; ?> 
