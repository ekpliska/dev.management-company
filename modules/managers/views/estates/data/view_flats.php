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
                <?= Html::button('<span class="glyphicon glyphicon-edit"></span>', [
                        'id' => 'edit-flat__link',
                        'class' => 'btn btn-link btn-sm',
                    ]) 
                ?>
                <?= Html::checkbox($flat_status, $flat['status'], [
                        'id' => 'check_status__flat',
                        'data-flat' => $flat['flats_id'],
                    ]) 
                ?>
            </td>
        </tr>
        <tr>
            <td width="10%">
                <?= FormatHelpers::formatUserPhoto($flat['user_photo']) ?>
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
