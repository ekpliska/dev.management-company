<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Просмотр квартир
 */
?>

<h4 class="title">
    Квартиры
    <span class="span-count"><?= count($flats) ?></span>
</h4>

<?php if (isset($flats) && $flats) : ?>
    <?php 
        foreach ($flats as $key => $flat) : 
        // Если баланс лицевого счета собственика отрицателен, то Собственник - должник
        $debtor = $flat['account']['account_balance'] < 0 ? true : false;
    ?>
    <div class="flats-info">
        <h3 class="<?= $debtor ? 'title-debtor' : 'title' ?>">
            <?= "Квартира {$flat['flats_number']}, подъезд {$flat['flats_porch']}" ?>
        </h3>
        
        <div class="flats-info__info">
            <div class="flats-info__info_image">
                <?= FormatHelpers::formatUserPhoto($flat['user_photo']) ?>
            </div>
            <div class="flats-info__info_content">
                <span>Собственник</span>
                <p class="client-name">
                    <?= FormatHelpers::formatFullUserName($flat['clients_surname'], $flat['clients_name'], $flat['clients_second_name'], true) ?>
                </p>                

                <?php if (isset($flats[$key]['note']) && $flats[$key]['note']) : ?>
                    <ul class="notes-flats-list" id="note_flat__tr-<?= $flat['flats_id'] ?>">
                        <li class="first-title"><span>Примечание</span></li>
                        <?php foreach ($flats[$key]['note'] as $note) : ?>
                        <li>
                            <?= $note['notes_name'] ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<div class="notice info">
    <p>В указанном доме квартиры не найдены</p>
</div>
<?php endif; ?>

<?php /*
<?php if (isset($flats) && $flats) : ?>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="10">
    <?php foreach ($flats as $key => $flat) : ?>
        <tr>
            <td colspan="2">
                <p class="label label-success">
                    
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
                
                </p>
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
        <?php if (isset($flats[$key]['note']) && $flats[$key]['note']) : ?>
        <tr id="note_flat__tr-<?= $flat['flats_id'] ?>">
            <td colspan="2">
                <span class="label label-primary">Примечания</span>
                <?= Html::button('<span class="glyphicon glyphicon-plus-sign"></span>', [
                        'class' => 'btn btn-link btn-sm',
                        'id' => 'add-note',
                        'data-flat' => $flat['flats_id'],
                    ]) ?>
            </td>
        </tr>
        <?php foreach ($flats[$key]['note'] as $note) : ?>
        <tr id="note_flat__tr-<?= $flat['flats_id'] ?>">
            <td colspan="2">
                    <?= $note['notes_name'] ?>
                    <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                            'class' => 'btn btn-link btn-sm flat_note__delete',
                            'data-note' => $note['notes_id']]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
<?php endif; ?>
