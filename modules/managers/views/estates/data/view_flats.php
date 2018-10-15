<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Просмотр квартир
 */
?>
<?php if (isset($flats) && $flats) : ?>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="10">
    <?php foreach ($flats as $flat) : ?>
        <tr>
            <td colspan="2">
                <?= FormatHelpers::flatAndPorch($flat['flats_number'], $flat['flats_porch']) ?>
                <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                        'id' => 'delete-characteristic__link',
                        'class' => 'btn btn-link btn-sm',
                    ]) 
                ?>
                <?= Html::checkbox($flat_status, $flat['status'], [
                        'data-flat-status' => $flat['flats_id'],
                    ]) 
                ?>
            </td>
        </tr>
        <tr>
            <td bgcolor="#c1c1c1">
                #foto
            </td>
            <td>
                Собственник
                <br/>
                <?= FormatHelpers::formatFullUserName($flat['clients_surname'], $flat['clients_name'], $flat['clients_second_name'], true) ?>
            </td>
        </tr>
        <tr style="border-bottom: 1px #c1c1c1 solid;">
            <td colspan="2">
                <!--desc-->
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
