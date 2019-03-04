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
            
            <?php if (Yii::$app->user->can('EstatesEdit')) : ?>
            <td>
                <?= Html::button('', [
                        'id' => 'delete-characteristic__link',
                        'class' => 'housing-block__btn-del',
                        'data-characteristic-id' => $characteristic['characteristics_id'],
                    ]) 
                ?>
            </td>
            <?php endif; ?>
            
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<div class="notice info">
    <p>Характеристики не заданы</p>
</div>
<?php endif; ?> 
